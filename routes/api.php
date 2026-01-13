<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\FeedController;
use App\Http\Controllers\User\BlockController;
use App\Services\GooglePlayService;
use App\Services\S3Service;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Test & Development Routes
|--------------------------------------------------------------------------
*/

// S3 Upload Test Route
Route::post('/test/s3-upload', function (Request $request) {
    try {
        $request->validate([
            'media' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi,webm|max:10240', // 10MB max
        ]);

        $s3Service = app(S3Service::class);
        $uploadResult = $s3Service->uploadMedia($request->file('media'));

        return response()->json([
            'status' => true,
            'message' => 'File uploaded successfully to S3!',
            'data' => [
                'path' => $uploadResult['path'],
                'url' => $uploadResult['url'],
                'type' => $uploadResult['type'],
                'folder' => $uploadResult['folder'],
                'mime_type' => $uploadResult['mime_type'],
                'file_name' => $uploadResult['file_name'],
                'file_size' => $uploadResult['file_size'],
            ],
        ]);
    } catch (\Throwable $exception) {
        Log::error('S3 upload test failed: ' . $exception->getMessage(), [
            'trace' => $exception->getTraceAsString(),
        ]);

        return response()->json([
            'status' => false,
            'message' => 'S3 upload failed: ' . $exception->getMessage(),
        ], 500);
    }
})->name('test.s3.upload');

Route::get('/subscribe/iap/google-ping', function () {
    try {
        app(GooglePlayService::class);
        return response()->json([
            'status' => true,
            'message' => 'Google Play service instantiated successfully. Credentials and configuration look good.',
        ]);
    } catch (\Throwable $exception) {
        Log::error('Google Play ping failed: ' . $exception->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Unable to instantiate Google Play service. Check configuration and credentials.',
        ], 500);
    }
});

Route::get('/subscribe/iap/google-test', function (Request $request) {
    $validated = $request->validate([
        'product' => 'required|string',
        'purchaseToken' => 'required|string',
        'packageName' => 'nullable|string',
    ]);

    $productId = config('services.google_play.products.' . $validated['product']) ?? $validated['product'];
    $packageName = $validated['packageName'] ?? config('services.google_play.package_name');

    if (empty($productId)) {
        return response()->json([
            'status' => false,
            'message' => 'Google Play product id is not configured.',
        ], 422);
    }

    if (empty($packageName)) {
        return response()->json([
            'status' => false,
            'message' => 'Google Play package name is not configured.',
        ], 422);
    }

    try {
        $googlePlay = app(GooglePlayService::class);
        $purchase = $googlePlay->getSubscriptionPurchase($productId, $validated['purchaseToken'], $packageName);

        return response()->json([
            'status' => true,
            'message' => 'Google Play connection successful.',
            'data' => [
                'acknowledgementState' => (int) $purchase->getAcknowledgementState(),
                'paymentState' => (int) $purchase->getPaymentState(),
                'expiryTimeMillis' => $purchase->getExpiryTimeMillis(),
                'startTimeMillis' => $purchase->getStartTimeMillis(),
            ],
        ]);
    } catch (\Throwable $exception) {
        Log::error('Google Play connection test failed: ' . $exception->getMessage(), [
            'product_id' => $productId,
            'package_name' => $packageName,
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Unable to confirm Google Play connection. See logs for details.',
        ], 502);
    }
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/user/dropdowns', [UserController::class, 'getDropdowns']);
Route::post('/register', [UserController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute
Route::post('/register-amcob', [UserController::class, 'registerAmcob'])->middleware('throttle:3,1'); // 3 attempts per minute
Route::post('/login', [UserController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/forget-password', [UserController::class, 'sendResetLink'])->middleware('throttle:3,1'); // 3 attempts per minute

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Chat & Messaging Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/conversations', [ChatController::class, 'getConversations'])->name('get.conversations');
    Route::post('/conversations/create', [ChatController::class, 'createConversation'])->name('create.conversation');
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages'])->name('get.message');
    Route::post('/messages/send', [ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::put('/messages/{message}', [ChatController::class, 'updateMessage'])->name('update.message'); // ✅ Add
    Route::delete('/messages/{message}', [ChatController::class, 'destroyMessage'])->name('destroy.message'); // ✅ Add
    Route::get('conversations/{conversation}/user', [ChatController::class, 'getUserForConversation'])->name('get.user.conversation');
    Route::post('/typing', [ChatController::class, 'userIsTyping'])->name('user.is.typing');
    Route::get('/check-conversation', [ChatController::class, 'checkConversation'])->name('check.conversation');
    Route::post('/messages/{message}/react', [ChatController::class, 'addReaction'])->name('add.reaction');
    Route::delete('/messages/{message}/react', [ChatController::class, 'removeReaction'])->name('remove.reaction');
    Route::post('/block-user', [BlockController::class, 'blockUser'])->name('block.user');
    Route::post('/unblock-user', [BlockController::class, 'unblockUser'])->name('unblock.user');
    Route::post('/check-block-status', [BlockController::class, 'checkBlockStatus'])->name('check.block.status');
    Route::get('/blocked-users', [BlockController::class, 'getBlockedUsers'])->name('blocked.users');
    /*
    |--------------------------------------------------------------------------
    | Firebase Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/user/ping', function (Request $request) {
        app(App\Services\FirebaseService::class)->updateUserOnlineStatus(auth()->id(), true);
        return response()->json(['status' => 'success']);
    })->name('user.ping');

    Route::post('/user/offline', function (Request $request) {
        app(App\Services\FirebaseService::class)->updateUserOnlineStatus(auth()->id(), false);
        return response()->json(['status' => 'success']);
    })->name('user.offline');

    Route::get('/firebase-token', function (Request $request) {
        try {
            \Log::info('Firebase token requested', ['user_id' => auth()->id()]);

            $firebaseService = app(\App\Services\FirebaseService::class);
            $customToken = $firebaseService->createCustomToken(auth()->id());

            \Log::info('Firebase token generated successfully', ['user_id' => auth()->id()]);

            return response()->json(['firebase_token' => $customToken]);
        } catch (\Exception $e) {
            \Log::error('Firebase token generation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to generate Firebase token',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('firebase.token');

    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/user/update/personal', [UserController::class, 'updatePersonal']);
    Route::post('/user/update/professional', [UserController::class, 'updateProfessional']);
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug']);
    Route::delete('/user/delete', [UserController::class, 'deleteUser']);

    /*
    |--------------------------------------------------------------------------
    | Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user/products', [ProductController::class, 'apiIndex']);
    Route::get('/user/products/{id}', [ProductController::class, 'apiShow']);
    Route::post('/user/products/store/{id?}', [ProductController::class, 'apiStore']);
    Route::delete('/user/products/delete/{id}', [ProductController::class, 'apiDelete']);

    /*
    |--------------------------------------------------------------------------
    | Services Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user/services', [ServiceController::class, 'apiIndex']);
    Route::get('/user/services/{id}', [ServiceController::class, 'apiShow']);
    Route::post('/user/services/store/{id?}', [ServiceController::class, 'apiStore']);
    Route::delete('/user/services/delete/{id}', [ServiceController::class, 'apiDelete']);

    /*
    |--------------------------------------------------------------------------
    | Qualifications/Education Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user/qualifications', [EducationController::class, 'apiIndex']);
    Route::get('/user/qualifications/{id}', [EducationController::class, 'apiShow']);
    Route::post('/user/qualifications/store/{id?}', [EducationController::class, 'apiStore']);
    Route::delete('/user/qualifications/delete/{id}', [EducationController::class, 'apiDelete']);

    /*
    |--------------------------------------------------------------------------
    | News Feed Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/feed/posts', [FeedController::class, 'getFeed'])->name('api.feed.posts');
    Route::get('/feed/posts/{slug}', [FeedController::class, 'getPost'])->name('api.feed.post.show');
    Route::post('/feed/posts', [FeedController::class, 'createPost'])->name('api.feed.post.create');
    Route::put('/feed/posts/{id}', [FeedController::class, 'updatePost'])->name('api.feed.post.update');
    Route::delete('/feed/posts/{id}', [FeedController::class, 'deletePost'])->name('api.feed.post.delete');
    Route::post('/feed/reactions', [FeedController::class, 'addReaction'])->name('api.feed.reaction.add');
    Route::delete('/feed/reactions', [FeedController::class, 'removeReaction'])->name('api.feed.reaction.remove');
    Route::post('/feed/posts/{postId}/comments', [FeedController::class, 'addComment'])->name('api.feed.comment.add');
    Route::put('/feed/comments/{commentId}', [FeedController::class, 'updateComment'])->name('api.feed.comment.update');
    Route::delete('/feed/comments/{commentId}', [FeedController::class, 'deleteComment'])->name('api.feed.comment.delete');
    Route::get('/feed/posts/{postId}/comments', [FeedController::class, 'getComments'])->name('api.feed.comments');
    Route::post('/feed/posts/{postId}/share', [FeedController::class, 'sharePost'])->name('api.feed.post.share');
    Route::get('/feed/user/{userId?}/posts', [FeedController::class, 'getUserPosts'])->name('api.feed.user.posts');

    /*
    |--------------------------------------------------------------------------
    | Discovery & Search Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/industries', [PageController::class, 'getIndustries']);
    Route::get('/industry-experts/{industry}', [PageController::class, 'getIndustryExperts']);
    Route::get('/smart-suggestions', [PageController::class, 'smartSuggestions']);
    Route::get('/our-community', [PageController::class, 'ourCommunity']);
    Route::get('/products', [PageController::class, 'getProducts']);
    Route::get('/services', [PageController::class, 'getServices']);
    Route::get('/search-filters', [SearchController::class, 'getDropdownFilters']);
    Route::get('/search', [SearchController::class, 'searchUserCompany']);
    Route::get('/get-suggestions', [SearchController::class, 'getSuggestions']);

    /*
    |--------------------------------------------------------------------------
    | Subscription & Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/subscribe/iap', [UserController::class, 'handleIapSubscription']);
    Route::get('/subscribe/iap/google-ping', function () {
        try {
            app(GooglePlayService::class);
            return response()->json([
                'status' => true,
                'message' => 'Google Play service instantiated successfully. Credentials and configuration look good.',
            ]);
        } catch (\Throwable $exception) {
            Log::error('Google Play ping failed: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unable to instantiate Google Play service. Check configuration and credentials.',
            ], 500);
        }
    });

    Route::get('/subscribe/iap/google-test', function (Request $request) {
        $validated = $request->validate([
            'product' => 'required|string',
            'purchaseToken' => 'required|string',
            'packageName' => 'nullable|string',
        ]);

        $productId = config('services.google_play.products.' . $validated['product']) ?? $validated['product'];
        $packageName = $validated['packageName'] ?? config('services.google_play.package_name');

        if (empty($productId)) {
            return response()->json([
                'status' => false,
                'message' => 'Google Play product id is not configured.',
            ], 422);
        }

        if (empty($packageName)) {
            return response()->json([
                'status' => false,
                'message' => 'Google Play package name is not configured.',
            ], 422);
        }

        try {
            $googlePlay = app(GooglePlayService::class);
            $purchase = $googlePlay->getSubscriptionPurchase($productId, $validated['purchaseToken'], $packageName);

            return response()->json([
                'status' => true,
                'message' => 'Google Play connection successful.',
                'data' => [
                    'acknowledgementState' => (int) $purchase->getAcknowledgementState(),
                    'paymentState' => (int) $purchase->getPaymentState(),
                    'expiryTimeMillis' => $purchase->getExpiryTimeMillis(),
                    'startTimeMillis' => $purchase->getStartTimeMillis(),
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('Google Play connection test failed: ' . $exception->getMessage(), [
                'product_id' => $productId,
                'package_name' => $packageName,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to confirm Google Play connection. See logs for details.',
            ], 502);
        }
    });
});

/*
|--------------------------------------------------------------------------
| API Key Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api.key')->group(function () {
    Route::get('/muslimlynk-users', [UserController::class, 'indexAllWithRelations']);
});
