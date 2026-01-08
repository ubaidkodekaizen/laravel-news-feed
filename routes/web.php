<?php

use App\Http\Controllers\User\CompanyController;
use App\Http\Controllers\User\EducationController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\FeedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\PageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\AuthorizeNetController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\SupportController;
use App\Models\Content\Blog;
use App\Models\Business\Company;
use App\Models\Content\Event;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\User;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Response;
use App\Services\GooglePlayService;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Test & Development Routes
|--------------------------------------------------------------------------
*/

Route::get('/iap/manual-ack', function () {
    $productId = 'premium_monthly';
    $purchaseToken = 'pfadblhdjleglpmfiglhokec.AO-J1OyUikJydQcgusdUbomZ44NrIS9Z-MTGX3qgW5vBw_XzDp9R1_1aQMOB4NlM_H1PWXVSNdo0-uY4gajhAp-OKbw8t6TDVw';
    $packageName = 'com.MuslimLynk';

    try {
        $googlePlay = app(GooglePlayService::class);
        $subscription = $googlePlay->getSubscriptionPurchase($productId, $purchaseToken, $packageName);
        $ackState = (int) $subscription->getAcknowledgementState();
        $isAcknowledged = $ackState === 1;

        if (!$isAcknowledged) {
            $googlePlay->acknowledgeSubscription($productId, $purchaseToken, null, $packageName);
            $ackMsg = 'Acknowledgment sent successfully ✅';
        } else {
            $ackMsg = 'Already acknowledged ✅';
        }

        return response()->json([
            'status' => true,
            'message' => $ackMsg,
            'subscription_details' => [
                'orderId' => $subscription->getOrderId(),
                'acknowledgementState' => $subscription->getAcknowledgementState(),
                'expiryTimeMillis' => $subscription->getExpiryTimeMillis(),
                'autoRenewing' => $subscription->getAutoRenewing(),
                'priceAmountMicros' => $subscription->getPriceAmountMicros(),
                'countryCode' => $subscription->getCountryCode(),
            ],
        ]);
    } catch (\Throwable $e) {
        Log::error('Manual acknowledgment failed: ' . $e->getMessage(), [
            'product_id' => $productId,
            'package_name' => $packageName,
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Acknowledgment failed ❌',
            'error' => $e->getMessage(),
        ], 500);
    }
});

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

Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from Laravel.', function ($message) {
            $message->to('s.u.shah68@gmail.com')->subject('Laravel Test Email');
        });
        return 'Test email sent successfully!';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

Route::get('/send-test-email', function () {
    $to = 's.u.shah68@gmail.com';
    $subject = 'Test Email from Laravel';
    $message = 'This is a test email.';
    $headers = "From: muslim.lynk@amcob.org\r\n";
    $headers .= "Reply-To: muslim.lynk@amcob.org\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (@mail($to, $subject, $message, $headers)) {
        return 'PHP Mail sent successfully!';
    } else {
        return 'PHP Mail failed.';
    }
});

/*
|--------------------------------------------------------------------------
| Public Routes (Unauthenticated)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Legal Pages
Route::get('/terms', function () {
    return view('legal.terms-of-service');
})->name('terms.of.service');

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy.policy');

Route::get('/child-safety-standard', function () {
    return view('legal.child-safety-standard');
})->name('child-safety-standard');

Route::get('/confirmation-email', function () {
    return view('emails.confirmation-email');
})->name('confirmation-email');

// Utility Routes
Route::get('/getSubcategories/{industryId}', [SearchController::class, 'getSubcategories'])->name('get-category');
Route::get('/get-suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestion');

// SEO Routes
Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create();

    $staticPages = [
        '/' => 1.0,
        '/terms' => 0.5,
        '/privacy-policy' => 0.5,
        '/child-safety-standard' => 0.5,
        '/feed' => 0.7,
        '/search' => 0.7,
        '/industry-experts' => 0.6,
    ];

    foreach ($staticPages as $page => $priority) {
        $sitemap->add(Url::create($page)->setPriority($priority));
    }

    $dynamicModels = [
        'blogs' => Blog::all(),
        'products' => Product::all(),
        'services' => Service::all(),
        'users' => User::whereNotNull('slug')->get(),
        'companies' => Company::whereNotNull('company_slug')->get(),
        'events' => Event::all(),
    ];

    foreach ($dynamicModels as $type => $items) {
        foreach ($items as $item) {
            $url = match ($type) {
                'blogs' => '/blog/' . $item->slug,
                'products' => '/products/' . $item->id,
                'services' => '/services/' . $item->id,
                'users' => '/user/profile/' . $item->slug,
                'companies' => '/company/' . $item->company_slug,
                'events' => '/events/' . $item->id,
            };

            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate($item->updated_at ?? now())
                    ->setPriority(0.8)
            );
        }
    }

    return $sitemap->toResponse(request());
});

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nDisallow:\nSitemap: " . url('/sitemap.xml');
    return Response::make($content, 200, ['Content-Type' => 'text/plain']);
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/sign-up', [AuthorizeNetController::class, 'index'])->name('register.form');
    Route::post('/authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');
    Route::post('/register', [UserController::class, 'register'])->name('register');

    // Login
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
    Route::get('/setup-password/{token}', [PasswordResetController::class, 'showSetupPasswordForm'])->name('password.setup');

    // Email Verification
    Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');

    // Admin Login
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/post/admin/login', [AdminController::class, 'login'])->name('post.admin.login');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role_id === 1) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role_id !== 4) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', RoleMiddleware::class . ':4'])->group(function () {
    // Dashboard & Navigation
    Route::get('/news-feed', [FeedController::class, 'index'])->name('news-feed');

    // News Feed API Routes (for AJAX calls)
    Route::prefix('feed')->group(function () {
        Route::get('/posts', [FeedController::class, 'getFeed'])->name('feed.posts');
        Route::get('/posts/{id}', [FeedController::class, 'getPost'])->name('feed.post.show');
        Route::post('/posts', [FeedController::class, 'createPost'])->name('feed.post.create');
        Route::put('/posts/{id}', [FeedController::class, 'updatePost'])->name('feed.post.update');
        Route::delete('/posts/{id}', [FeedController::class, 'deletePost'])->name('feed.post.delete');
        Route::post('/reactions', [FeedController::class, 'addReaction'])->name('feed.reaction.add');
        Route::delete('/reactions', [FeedController::class, 'removeReaction'])->name('feed.reaction.remove');
        Route::post('/posts/{postId}/comments', [FeedController::class, 'addComment'])->name('feed.comment.add');
        Route::put('/comments/{commentId}', [FeedController::class, 'updateComment'])->name('feed.comment.update');
        Route::delete('/comments/{commentId}', [FeedController::class, 'deleteComment'])->name('feed.comment.delete');
        Route::get('/posts/{postId}/comments', [FeedController::class, 'getComments'])->name('feed.comments');
        Route::post('/posts/{postId}/share', [FeedController::class, 'sharePost'])->name('feed.post.share');
        Route::get('/user/{userId?}/posts', [FeedController::class, 'getUserPosts'])->name('feed.user.posts');
    });

    Route::get('/inbox', function () {
        return view('user.inbox');
    })->name('inbox');

    // User Profile & Details
    Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
    Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug'])->name('user.profile');
    Route::get('/user/mosque/search', [UserController::class, 'searchMosque'])->name('user.mosque.search');
    Route::post('/user/mosque/store', [UserController::class, 'storeMosque'])->name('user.mosque.store');

    // Company
    Route::get('/user/company/details', [CompanyController::class, 'showUserCompanyForm'])->name('user.company.details');
    Route::post('/user/company/update', [CompanyController::class, 'storeCompanyDetails'])->name('user.company.update');
    Route::get('/user/company/{companySlug}', [CompanyController::class, 'showCompanyBySlug'])->name('company.profile');

    // Products
    Route::get('/user/products', [ProductController::class, 'index'])->name('user.products');
    Route::get('/user/products/add', [ProductController::class, 'addEditProduct'])->name('user.add.product');
    Route::get('/user/products/edit/{id}', [ProductController::class, 'addEditProduct'])->name('user.edit.product');
    Route::post('/user/products/store/{id?}', [ProductController::class, 'storeProduct'])->name('user.store.product');
    Route::delete('/user/products/delete/{id}', [ProductController::class, 'deleteProduct'])->name('user.delete.product');

    // Services
    Route::get('/user/services', [ServiceController::class, 'index'])->name('user.services');
    Route::get('/user/services/add', [ServiceController::class, 'addEditService'])->name('user.add.service');
    Route::get('/user/services/edit/{id}', [ServiceController::class, 'addEditService'])->name('user.edit.service');
    Route::post('/user/services/store/{id?}', [ServiceController::class, 'storeService'])->name('user.store.service');
    Route::delete('/user/services/delete/{id}', [ServiceController::class, 'deleteService'])->name('user.delete.service');

    // Qualifications/Education
    Route::get('/user/qualifications', [EducationController::class, 'index'])->name('user.qualifications');
    Route::get('/user/qualifications/add', [EducationController::class, 'addEditEducation'])->name('user.add.qualifications');
    Route::get('/user/qualifications/edit/{id}', [EducationController::class, 'addEditEducation'])->name('user.edit.qualifications');
    Route::post('/user/qualifications/store/{id?}', [EducationController::class, 'storeEducation'])->name('user.store.qualifications');
    Route::delete('/user/qualifications/delete/{id}', [EducationController::class, 'deleteEducation'])->name('user.delete.qualifications');

    // Subscriptions
    Route::get('/user/subscriptions', [SubscriptionController::class, 'index'])->name('user.subscriptions');
    Route::get('/user/subscriptions/add', [SubscriptionController::class, 'addSubscription'])->name('user.add.subscriptions');
    Route::post('/user/subscriptions/process-payment', [SubscriptionController::class, 'processPayment'])->name('user.subscriptions.process-payment');

    // Support/Feedback
    Route::get('/feedback', [SupportController::class, 'create'])->name('support.create');
    Route::post('/feedback/create', [SupportController::class, 'store'])->name('support.submit');

    // Search & Discovery
    Route::get('/search', [SearchController::class, 'SearchUserCompany'])->name('search');
    Route::get('/our-community', [PageController::class, 'ourCommunity'])->name('our.community');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::get('/products', [PageController::class, 'products'])->name('products');
    Route::get('/industry-experts/{industry}', [PageController::class, 'industryExperts'])->name('industry');
    Route::get('/smart-suggestion', [PageController::class, 'smartSuggestion'])->name('smart.suggestion');

    // API Token
    Route::get('user/get-token', function () {
        return response()->json(['token' => session('sanctum_token')]);
    })->name("user.token");
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', RoleMiddleware::class . ':1'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Users Management
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/add', [AdminController::class, 'addUser'])->name('admin.add.user');
    Route::post('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.create.user');
    Route::get('/admin/user/profile/{id}', [AdminController::class, 'showUserById'])->name('admin.user.profile');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::post('/admin/user/update', [AdminController::class, 'updateUserDetails'])->name('admin.user.update');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/admin-reset-link', [AdminController::class, 'adminResetLink'])->name('admin.reset.link');

    // Company Management
    Route::get('/admin/company/edit/{id}', [AdminController::class, 'editCompany'])->name('admin.company.edit');
    Route::post('/admin/company/update', [AdminController::class, 'updateCompanyDetails'])->name('admin.company.update');

    // Blogs Management
    Route::get('/admin/blogs', [AdminController::class, 'adminBlogs'])->name('admin.blogs');
    Route::get('/admin/add-blog', [AdminController::class, 'addBlog'])->name('admin.add.blog');
    Route::post('/admin/store-blog/{id?}', [AdminController::class, 'storeBlog'])->name('admin.store.blog');
    Route::get('/admin/edit-blog/{id}', [AdminController::class, 'editBlog'])->name('admin.edit.blog');
    Route::delete('/admin/delete-blog/{id}', [AdminController::class, 'deleteBlog'])->name('admin.delete.blog');

    // Events Management
    Route::get('/admin/events', [AdminController::class, 'adminEvents'])->name('admin.events');
    Route::get('/admin/add-event/', [AdminController::class, 'addEvent'])->name('admin.add.event');
    Route::post('/admin/store-event/{id?}', [AdminController::class, 'storeEvent'])->name('admin.store.event');
    Route::get('/admin/edit-event/{id}', [AdminController::class, 'editEvent'])->name('admin.edit.event');
    Route::delete('/admin/delete-event/{id}', [AdminController::class, 'deleteEvent'])->name('admin.delete.event');

    // Subscriptions Management
    Route::get('/admin/subscriptions', [AdminController::class, 'showSubscriptions'])->name('admin.subscriptions');
});

// In routes/web.php
// Route::get('/debug-firebase-config', function() {
//     return response()->json([
//         'credentials_file' => config('firebase.credentials.file'),
//         'database_url' => config('firebase.database.url'),
//         'project_id' => config('firebase.project_id'),
//         'file_exists' => file_exists(config('firebase.credentials.file')),
//         'storage_path' => storage_path('app/firebase-credentials.json'),
//         'env_credentials' => env('FIREBASE_CREDENTIALS'),
//         'env_database' => env('FIREBASE_DATABASE_URL'),
//     ]);
// });

// Route::get('/test-firebase-operations', function() {
//     try {
//         $firebaseService = app(\App\Services\FirebaseService::class);
//         $userId = auth()->id();

//         // Test 1: Update unread count
//         $firebaseService->updateUnreadCount($userId, 123, true);
//         \Log::info('Test: Updated unread count for conversation 123');

//         // Test 2: Get the database instance to read back
//         $reflection = new \ReflectionClass($firebaseService);
//         $property = $reflection->getProperty('database');
//         $property->setAccessible(true);
//         $database = $property->getValue($firebaseService);

//         $unreadCount = $database->getReference("unread_counts/{$userId}/123")->getValue();
//         $totalUnread = $database->getReference("unread_totals/{$userId}")->getValue();

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Firebase operations completed',
//             'data' => [
//                 'conversation_123_unread' => $unreadCount,
//                 'total_unread' => $totalUnread,
//                 'user_id' => $userId
//             ]
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Firebase test failed: ' . $e->getMessage(), [
//             'trace' => $e->getTraceAsString()
//         ]);

//         return response()->json([
//             'status' => 'error',
//             'message' => $e->getMessage(),
//             'trace' => $e->getTraceAsString()
//         ], 500);
//     }
// })->middleware('auth:sanctum');
/*
|--------------------------------------------------------------------------
| Logout Routes
|--------------------------------------------------------------------------
*/

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');

Route::get('/admin/logout', function () {
    Auth::logout();
    return redirect()->route('admin.login');
})->name('admin.logout');
