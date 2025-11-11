<?php
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\PusherController;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Response;
use App\Services\GooglePlayService;
use Illuminate\Support\Facades\Log;


// 🔹 Static test data from your purchase
Route::get('/iap/manual-ack', function () {
    // 🔹 Static test data from your purchase
    $productId = 'premium_monthly';
    $purchaseToken = 'pfadblhdjleglpmfiglhokec.AO-J1OyUikJydQcgusdUbomZ44NrIS9Z-MTGX3qgW5vBw_XzDp9R1_1aQMOB4NlM_H1PWXVSNdo0-uY4gajhAp-OKbw8t6TDVw';
    $packageName = 'com.MuslimLynk';

    try {
        /** @var GooglePlayService $googlePlay */
        $googlePlay = app(GooglePlayService::class);

        // Step 1: Fetch subscription details
        $subscription = $googlePlay->getSubscriptionPurchase($productId, $purchaseToken, $packageName);
        $ackState = (int) $subscription->getAcknowledgementState();
        $isAcknowledged = $ackState === 1;

        // Step 2: Acknowledge only if not already acknowledged
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
        Mail::raw('This is a test email from Laravel.', function ($message) {
            $message->to('s.u.shah68@gmail.com')
                ->subject('Laravel Test Email');
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





// User Routes

Route::middleware(['auth', RoleMiddleware::class . ':4'])->group(function () {

    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    Route::get('/news-feed', function () {
        return view('user.news-feed');
    })->name('news-feed');

    Route::get('/user/products', [ProductController::class, 'index'])->name('user.products');
    Route::get('/user/products/add', [ProductController::class, 'addEditProduct'])->name('user.add.product');
    Route::get('/user/products/edit/{id}', [ProductController::class, 'addEditProduct'])->name('user.edit.product');
    Route::post('/user/products/store/{id?}', [ProductController::class, 'storeProduct'])->name('user.store.product');
    Route::delete('/user/products/delete/{id}', [ProductController::class, 'deleteProduct'])->name('user.delete.product');


    Route::get('/user/services', [ServiceController::class, 'index'])->name('user.services');
    Route::get('/user/services/add', [ServiceController::class, 'addEditService'])->name('user.add.service');
    Route::get('/user/services/edit/{id}', [ServiceController::class, 'addEditService'])->name('user.edit.service');
    Route::post('/user/services/store/{id?}', [ServiceController::class, 'storeService'])->name('user.store.service');
    Route::delete('/user/services/delete/{id}', [ServiceController::class, 'deleteService'])->name('user.delete.service');


    Route::get('/user/qualifications', [EducationController::class, 'index'])->name('user.qualifications');
    Route::get('/user/qualifications/add', [EducationController::class, 'addEditEducation'])->name('user.add.qualifications');
    Route::get('/user/qualifications/edit/{id}', [EducationController::class, 'addEditEducation'])->name('user.edit.qualifications');
    Route::post('/user/qualifications/store/{id?}', [EducationController::class, 'storeEducation'])->name('user.store.qualifications');
    Route::delete('/user/qualifications/delete/{id}', [EducationController::class, 'deleteEducation'])->name('user.delete.qualifications');



    Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
    Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug'])->name('user.profile');

    // Search mosques by zip, city, or state
    Route::get('/user/mosque/search', [UserController::class, 'searchMosque'])->name('user.mosque.search');
    Route::post('/user/mosque/store', [UserController::class, 'storeMosque'])->name('user.mosque.store');


    Route::get('/user/company/details', [CompanyController::class, 'showUserCompanyForm'])->name('user.company.details');
    Route::post('/user/company/update', [CompanyController::class, 'storeCompanyDetails'])->name('user.company.update');
    Route::get('/user/company/{companySlug}', [CompanyController::class, 'showCompanyBySlug'])->name('company.profile');

    Route::get('/search', [SearchController::class, 'SearchUserCompany'])->name('search');
    Route::get('/our-community', [PageController::class, 'ourCommunity'])->name('our.community');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::get('/products', [PageController::class, 'products'])->name('products');
    Route::get('/industry-experts/{industry}', [PageController::class, 'industryExperts'])->name('industry');

    Route::get('/smart-suggestion', [PageController::class, 'smartSuggestion'])->name('smart.suggestion');



    Route::get('user/get-token', function () {
        return response()->json(['token' => session('sanctum_token')]);
    })->name("user.token");
    Route::post('/pusher/user-auth', [PusherController::class, 'pusherAuth']);
});



// Admin Routes
Route::middleware(['auth', RoleMiddleware::class . ':1'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    //Users
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/add', [AdminController::class, 'addUser'])->name('admin.add.user');
    Route::post('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.create.user');
    Route::get('/admin/user/profile/{id}', [AdminController::class, 'showUserById'])->name('admin.user.profile');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::post('/admin/user/update', [AdminController::class, 'updateUserDetails'])->name('admin.user.update');
    Route::get('/admin/company/edit/{id}', [AdminController::class, 'editCompany'])->name('admin.company.edit');
    Route::post('/admin/company/update', [AdminController::class, 'updateCompanyDetails'])->name('admin.company.update');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/admin-reset-link', [AdminController::class, 'adminResetLink'])->name('admin.reset.link');

    //Blogs
    Route::get('/admin/blogs', [AdminController::class, 'adminBlogs'])->name('admin.blogs');
    Route::get('/admin/add-blog', [AdminController::class, 'addBlog'])->name('admin.add.blog');
    Route::post('/admin/store-blog/{id?}', [AdminController::class, 'storeBlog'])->name('admin.store.blog');
    Route::get('/admin/edit-blog/{id}', [AdminController::class, 'editBlog'])->name('admin.edit.blog');
    Route::delete('/admin/delete-blog/{id}', [AdminController::class, 'deleteBlog'])->name('admin.delete.blog');


    //Events
    Route::get('/admin/events', [AdminController::class, 'adminEvents'])->name('admin.events');
    Route::get('/admin/add-event/', [AdminController::class, 'addEvent'])->name('admin.add.event');
    Route::post('/admin/store-event/{id?}', [AdminController::class, 'storeEvent'])->name('admin.store.event');
    Route::get('/admin/edit-event/{id}', [AdminController::class, 'editEvent'])->name('admin.edit.event');
    Route::delete('/admin/delete-event/{id}', [AdminController::class, 'deleteEvent'])->name('admin.delete.event');

    //Subscriptions
    Route::get('/admin/subscriptions', [AdminController::class, 'showSubscriptions'])->name('admin.subscriptions');

});




Route::middleware('guest')->group(function () {

    Route::get('/sign-up', [AuthorizeNetController::class, 'index'])->name('register.form');
    Route::post('/authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');

    // User Registration
    Route::post('/register', [UserController::class, 'register'])->name('register');

    // User Login
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    // Forgot Password Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

    // Reset Password Routes
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
    Route::get('/setup-password/{token}', [PasswordResetController::class, 'showSetupPasswordForm'])->name('password.setup');

    // Admin Routes
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/post/admin/login', [AdminController::class, 'login'])->name('post.admin.login');


});

// Unauthenticated Routes

Route::get('/', function () {
    return view('home');
})->name('home');



Route::get('/getSubcategories/{industryId}', [SearchController::class, 'getSubcategories'])->name('get-category');

Route::get('/get-suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestion');


Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');

Route::get('/admin/logout', function () {
    Auth::logout();
    return redirect()->route('admin.login');
})->name('admin.logout');

Route::get('/terms', function () {
    return view('terms-of-service');
})->name('terms.of.service');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::get('/child-safety-standard', function () {
    return view('child-safety-standard');
})->name('child-safety-standard');

Route::get('/confirmation-email', function () {
    return view('emails.confirmation-email');
})->name('confirmation-email');


Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create();

    // **Static Pages**
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

    // **Dynamic Content**
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
                'products' => '/products/' . $item->id, // Adjusted URL
                'services' => '/services/' . $item->id, // Adjusted URL
                'users' => '/user/profile/' . $item->slug,
                'companies' => '/company/' . $item->company_slug,
                'events' => '/events/' . $item->id, // Adjusted URL
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