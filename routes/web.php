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
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ManagersController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\AuthorizeNetController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Test & Development Routes
|--------------------------------------------------------------------------
*/

Route::get('/iap/manual-ack', [TestController::class, 'manualIapAck'])->name('test.iap.manual-ack');
Route::get('/subscribe/iap/google-ping', [TestController::class, 'googlePlayPing'])->name('test.google-play.ping');
Route::get('/test-email', [TestController::class, 'testEmail'])->name('test.email');
Route::get('/send-test-email', [TestController::class, 'sendTestEmail'])->name('test.send-email');

/*
|--------------------------------------------------------------------------
| API Documentation Routes (Password Protected)
|--------------------------------------------------------------------------
*/

Route::get('/api-doc', function () {
    // Check if user is authenticated
    if (!session()->has('api_doc_authenticated')) {
        return view('api-doc.password');
    }

    return view('api-doc.index');
})->name('api.doc');

Route::post('/api-doc/authenticate', function (\Illuminate\Http\Request $request) {
    $inputPassword = $request->input('password');
    $storedPassword = env('API_PASSWORD', '884588rvkwd56zb640');

    if ($inputPassword === $storedPassword) {
        session(['api_doc_authenticated' => true]);
        return redirect()->route('api.doc');
    }

    return back()->withErrors(['password' => 'Invalid password. Please try again.']);
})->name('api.doc.authenticate');

Route::get('/api-doc/logout', function () {
    session()->forget('api_doc_authenticated');
    return redirect()->route('api.doc');
})->name('api.doc.logout');

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
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

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
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', RoleMiddleware::class . ':4'])->group(function () {
    // Dashboard & Navigation
    Route::get('/news-feed', [FeedController::class, 'index'])->name('news-feed');

    // News Feed API Routes (for AJAX calls)
    Route::prefix('feed')->group(function () {
       // Post CRUD
        Route::get('/posts', [FeedController::class, 'getFeed'])->name('feed.posts');



        Route::post('/posts', [FeedController::class, 'createPost'])->name('feed.post.create');
        Route::put('/posts/{id}', [FeedController::class, 'updatePost'])->name('feed.post.update');
        Route::delete('/posts/{id}', [FeedController::class, 'deletePost'])->name('feed.post.delete');

        // Reactions
        Route::post('/reactions', [FeedController::class, 'addReaction'])->name('feed.reaction.add');
        Route::delete('/reactions', [FeedController::class, 'removeReaction'])->name('feed.reaction.remove');
        Route::get('/posts/{postId}/reactions-count', [FeedController::class, 'getReactionCount'])->name('feed.post.reactions.count');
        Route::get('/posts/{postId}/reactions-list', [FeedController::class, 'getReactionsList'])->name('feed.post.reactions.list');

        // Comments
        Route::post('/posts/{postId}/comments', [FeedController::class, 'addComment'])->name('feed.comment.add');
        Route::put('/comments/{commentId}', [FeedController::class, 'updateComment'])->name('feed.comment.update');
        Route::delete('/comments/{commentId}', [FeedController::class, 'deleteComment'])->name('feed.comment.delete');
        Route::get('/posts/{postId}/comments', [FeedController::class, 'getComments'])->name('feed.comments');
        Route::get('/posts/{postId}/comments-count', [FeedController::class, 'getCommentCount'])->name('feed.post.comments.count');

        // Sharing
        Route::post('/posts/{postId}/share', [FeedController::class, 'sharePost'])->name('feed.post.share');
        Route::get('/posts/{postId}/shares-list', [FeedController::class, 'getSharesList'])->name('feed.post.shares.list');

        // User posts
        Route::get('/user/{userId?}/posts', [FeedController::class, 'getUserPosts'])->name('feed.user.posts');

        // IMPORTANT: Single post detail page (must be BEFORE the posts/{slug} API route)
        Route::get('/posts/{slug}', [FeedController::class, 'showPostPage'])
            ->where('slug', '[a-z0-9\-]+')
            ->name('feed.post.page');
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

// Admin routes accessible by Admin (1), Manager (2), and Editor (3)
Route::middleware(['auth', RoleMiddleware::class . ':1|2|3'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/chart-data', [AdminController::class, 'getChartData'])->name('admin.dashboard.chart-data');

    // Users Management
    Route::get('/admin/users', [AdminUserController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/add', [AdminUserController::class, 'addUser'])->name('admin.add.user');
    Route::post('/admin/users/create', [AdminUserController::class, 'createUser'])->name('admin.create.user');
    Route::get('/admin/user/profile/{id}', [AdminUserController::class, 'showUserById'])->name('admin.user.profile');
    Route::get('/admin/user/edit/{id}', [AdminUserController::class, 'editUser'])->name('admin.user.edit');
    Route::post('/admin/user/update', [AdminUserController::class, 'updateUserDetails'])->name('admin.user.update');
    Route::delete('/admin/delete-user/{id}', [AdminUserController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/restore-user/{id}', [AdminUserController::class, 'restoreUser'])->name('admin.restore.user');
    Route::post('/admin/admin-reset-link', [AdminUserController::class, 'adminResetLink'])->name('admin.reset.link');

    // Company Management
    Route::get('/admin/company/edit/{id}', [AdminUserController::class, 'editCompany'])->name('admin.company.edit');
    Route::post('/admin/company/update', [AdminUserController::class, 'updateCompanyDetails'])->name('admin.company.update');

    // Blogs Management
    Route::get('/admin/blogs', [BlogController::class, 'index'])->name('admin.blogs');
    Route::get('/admin/blogs/add', [BlogController::class, 'create'])->name('admin.add.blog');
    Route::post('/admin/blogs', [BlogController::class, 'store'])->name('admin.store.blog');
    Route::get('/admin/blogs/{id}', [BlogController::class, 'show'])->name('admin.view.blog');
    Route::get('/admin/blogs/{id}/edit', [BlogController::class, 'edit'])->name('admin.edit.blog');
    Route::put('/admin/blogs/{id}', [BlogController::class, 'update'])->name('admin.update.blog');
    Route::delete('/admin/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.delete.blog');
    Route::post('/admin/blogs/{id}/restore', [BlogController::class, 'restore'])->name('admin.restore.blog');

    // Events Management
    Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events');
    Route::get('/admin/events/add', [EventController::class, 'create'])->name('admin.add.event');
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.store.event');
    Route::get('/admin/events/{id}', [EventController::class, 'show'])->name('admin.view.event');
    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit'])->name('admin.edit.event');
    Route::put('/admin/events/{id}', [EventController::class, 'update'])->name('admin.update.event');
    Route::delete('/admin/events/{id}', [EventController::class, 'destroy'])->name('admin.delete.event');
    Route::post('/admin/events/{id}/restore', [EventController::class, 'restore'])->name('admin.restore.event');

    // Ads Management
    Route::get('/admin/ads', [AdController::class, 'index'])->name('admin.ads');
    Route::get('/admin/ads/add', [AdController::class, 'create'])->name('admin.add.ad');
    Route::post('/admin/ads', [AdController::class, 'store'])->name('admin.store.ad');
    Route::get('/admin/ads/{id}/edit', [AdController::class, 'edit'])->name('admin.edit.ad');
    Route::put('/admin/ads/{id}', [AdController::class, 'update'])->name('admin.update.ad');
    Route::delete('/admin/ads/{id}', [AdController::class, 'destroy'])->name('admin.delete.ad');
    Route::post('/admin/ads/{id}/restore', [AdController::class, 'restore'])->name('admin.restore.ad');
    Route::patch('/admin/ads/{id}/toggle-featured', [AdController::class, 'toggleFeatured'])->name('admin.toggle.featured');
    Route::patch('/admin/ads/{id}/toggle-status', [AdController::class, 'toggleStatus'])->name('admin.toggle.status');

    // Subscriptions Management
    Route::get('/admin/subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin.subscriptions');

    // Scheduler Logs Management (Admin only)
    Route::get('/admin/scheduler-logs', [\App\Http\Controllers\Admin\SchedulerLogController::class, 'index'])->name('admin.scheduler-logs');
    Route::get('/admin/scheduler-logs/{id}', [\App\Http\Controllers\Admin\SchedulerLogController::class, 'show'])->name('admin.scheduler-logs.show');
    Route::delete('/admin/scheduler-logs/{id}', [\App\Http\Controllers\Admin\SchedulerLogController::class, 'destroy'])->name('admin.scheduler-logs.destroy');
    Route::post('/admin/scheduler-logs/{id}/restore', [\App\Http\Controllers\Admin\SchedulerLogController::class, 'restore'])->name('admin.scheduler-logs.restore');

    // Products/Services Management
    Route::get('/admin/products-services', [AdminController::class, 'showProductsServices'])->name('admin.products-services');
    Route::get('/admin/product/{id}', [AdminProductController::class, 'view'])->name('admin.view.product');
    Route::get('/admin/service/{id}', [AdminServiceController::class, 'view'])->name('admin.view.service');
    Route::get('/admin/product/edit/{id}', [AdminProductController::class, 'edit'])->name('admin.edit.product');
    Route::get('/admin/service/edit/{id}', [AdminServiceController::class, 'edit'])->name('admin.edit.service');
    Route::put('/admin/product/update/{id}', [AdminProductController::class, 'update'])->name('admin.update.product');
    Route::put('/admin/service/update/{id}', [AdminServiceController::class, 'update'])->name('admin.update.service');
    Route::delete('/admin/product/delete/{id}', [AdminProductController::class, 'delete'])->name('admin.delete.product');
    Route::delete('/admin/service/delete/{id}', [AdminServiceController::class, 'delete'])->name('admin.delete.service');
    Route::post('/admin/product/restore/{id}', [AdminProductController::class, 'restore'])->name('admin.restore.product');
    Route::post('/admin/service/restore/{id}', [AdminServiceController::class, 'restore'])->name('admin.restore.service');
});

// Managers Management - Only accessible by Admin (role_id = 1)
Route::middleware(['auth', RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/admin/managers', [ManagersController::class, 'index'])->name('admin.managers');
    Route::get('/admin/managers/add', [ManagersController::class, 'create'])->name('admin.add.manager');
    Route::post('/admin/managers/create', [ManagersController::class, 'store'])->name('admin.create.manager');
    Route::get('/admin/managers/edit/{id}', [ManagersController::class, 'edit'])->name('admin.edit.manager');
    Route::put('/admin/managers/update/{id}', [ManagersController::class, 'update'])->name('admin.update.manager');
    Route::post('/admin/managers/update-permissions/{id}', [ManagersController::class, 'updatePermissions'])->name('admin.update.manager.permissions');
    Route::delete('/admin/managers/delete/{id}', [ManagersController::class, 'destroy'])->name('admin.delete.manager');
    Route::post('/admin/managers/restore/{id}', [ManagersController::class, 'restore'])->name('admin.restore.manager');
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

// Admin logout - accessible to all authenticated admin users (roles 1, 2, 3)
Route::middleware('auth')->group(function () {
    Route::get('/admin/logout', function () {
        Auth::logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');
});
