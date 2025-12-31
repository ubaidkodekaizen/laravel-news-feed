<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\EducationController;
use App\Services\GooglePlayService;
use Illuminate\Support\Facades\Log;


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
    /** @var GooglePlayService $googlePlay */
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


// Apply middleware at the route level
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/conversations', [ChatController::class, 'getConversations'])->name('get.conversations');
  Route::post('/conversations/create', [ChatController::class, 'createConversation'])->name('create.conversation');
  Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages'])->name('get.message');
  Route::post('/messages/send', [ChatController::class, 'sendMessage'])->name('sendMessage');
  Route::get('conversations/{conversation}/user', [ChatController::class, 'getUserForConversation'])->name('get.user.conversation');
  Route::post('/typing', [ChatController::class, 'userIsTyping'])->name('user.is.typing');
  Route::get('/check-conversation', [ChatController::class, 'checkConversation'])->name('check.conversation');

  // Add reaction to a message
  Route::post('/messages/{message}/react', [ChatController::class, 'addReaction'])->name('add.reaction');
  // Remove reaction from a message
  Route::delete('/messages/{message}/react', [ChatController::class, 'removeReaction'])->name('remove.reaction');

   Route::post('/user/ping', function (Request $request) {
        app(App\Services\FirebaseService::class)->updateUserOnlineStatus(auth()->id(), true);
        return response()->json(['status' => 'success']);
    })->name('user.ping');

  Route::post('/user/offline', function (Request $request) {
        app(App\Services\FirebaseService::class)->updateUserOnlineStatus(auth()->id(), false);
        return response()->json(['status' => 'success']);
    })->name('user.offline');

   // Firebase custom token endpoint
    Route::get('/firebase-token', function (Request $request) {
        try {
            $firebaseService = app(App\Services\FirebaseService::class);
            $customToken = $firebaseService->createCustomToken(auth()->id());

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

  // Mobile API Routes


  Route::post('/user/update/personal', [UserController::class, 'updatePersonal']);
  Route::post('/user/update/professional', [UserController::class, 'updateProfessional']);

  Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug']);
  Route::delete('/user/delete', [UserController::class, 'deleteUser']);



  Route::get('/user/products', [ProductController::class, 'apiIndex']);
  Route::get('/user/products/{id}', [ProductController::class, 'apiShow']);
  Route::post('/user/products/store/{id?}', [ProductController::class, 'apiStore']);
  Route::delete('/user/products/delete/{id}', [ProductController::class, 'apiDelete']);

  Route::get('/user/services', [ServiceController::class, 'apiIndex']);
  Route::get('/user/services/{id}', [ServiceController::class, 'apiShow']);
  Route::post('/user/services/store/{id?}', [ServiceController::class, 'apiStore']);
  Route::delete('/user/services/delete/{id}', [ServiceController::class, 'apiDelete']);

  Route::get('/user/qualifications', [EducationController::class, 'apiIndex']);
  Route::get('/user/qualifications/{id}', [EducationController::class, 'apiShow']);
  Route::post('/user/qualifications/store/{id?}', [EducationController::class, 'apiStore']);
  Route::delete('/user/qualifications/delete/{id}', [EducationController::class, 'apiDelete']);

  Route::get('/industries', [PageController::class, 'getIndustries']);
  Route::get('/industry-experts/{industry}', [PageController::class, 'getIndustryExperts']);
  Route::get('/smart-suggestions', [PageController::class, 'smartSuggestions']);
  Route::get('/our-community', [PageController::class, 'ourCommunity']);

  Route::get('/products', [PageController::class, 'getProducts']);
  Route::get('/services', [PageController::class, 'getServices']);

  Route::get('/search-filters', [SearchController::class, 'getDropdownFilters']);
  Route::get('/search', [SearchController::class, 'searchUserCompany']);
  Route::get('/get-suggestions', [SearchController::class, 'getSuggestions']);

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
      /** @var GooglePlayService $googlePlay */
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
Route::middleware('api.key')->group(function () {
  Route::get('/muslimlynk-users', [UserController::class, 'indexAllWithRelations']);
});

Route::get('/user/dropdowns', [UserController::class, 'getDropdowns']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/register-amcob', [UserController::class, 'registerAmcob']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/forget-password', [UserController::class, 'sendResetLink']);


