<?php
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Middleware\RoleMiddleware;



Route::get('/', function () { return view('welcome'); });
Route::get('/getSubcategories/{industryId}', [SearchController::class, 'getSubcategories'])->name('get-category');
Route::get('/get-suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestion');





Route::middleware(['auth', RoleMiddleware::class . ':4'])->group(function () {

    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
    Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug'])->name('user.profile');
    Route::get('/user/company/details', [CompanyController::class, 'showUserCompanyForm'])->name('user.company.details');
    Route::post('/user/company/update', [CompanyController::class, 'storeCompanyDetails'])->name('user.company.update');
    Route::get('/user/company/{companySlug}', [CompanyController::class, 'showCompanyBySlug'])->name('company.profile');
    Route::get('/search', [SearchController::class, 'SearchUserCompany'])->name('search');
    Route::get('/feed', function () {return view('feed');})->name('feed');
    
    
});




Route::middleware(['auth', RoleMiddleware::class . ':1'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    
    //Users
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/user/profile/{id}', [AdminController::class, 'showUserById'])->name('admin.user.profile');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::post('/admin/user/update', [AdminController::class, 'updateUserDetails'])->name('admin.user.update');
    Route::get('/admin/company/edit/{id}', [AdminController::class, 'editCompany'])->name('admin.company.edit');
    Route::post('/admin/company/update', [AdminController::class, 'updateCompanyDetails'])->name('admin.company.update');
     
    //Blogs
    Route::get('/admin/blogs', [AdminController::class, 'adminBlogs'])->name('admin.blogs');
    Route::get('/admin/add-blog', [AdminController::class, 'addBlog'])->name('admin.add.blog');
    Route::post('/admin/store-blog/{id?}', [AdminController::class, 'storeBlog'])->name('admin.store.blog');
    Route::get('/admin/edit-blog/{id}', [AdminController::class, 'editBlog'])->name('admin.edit.blog');
    Route::delete('/admin/delete-blog/{id}', [AdminController::class, 'deleteBlog'])->name('admin.delete.blog');

    
    //Events
    Route::get('/admin/events', [AdminController::class, 'adminEvents'])->name('admin.events');
    Route::get('/admin/add-event', [AdminController::class, 'addEvent'])->name('admin.add.event');
    Route::get('/admin/edit-event/{id}', [AdminController::class, 'editEvent'])->name('admin.edit.event');
   
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






Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');

Route::get('/admin/logout', function () {
    Auth::logout();
    return redirect()->route('admin.login');
})->name('admin.logout');

