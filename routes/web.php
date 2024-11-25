<?php


use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;




Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
    Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug'])->name('user.profile');

    Route::get('user/company/details', [CompanyController::class, 'showUserCompanyForm'])->name('user.company.details');
    Route::post('/user/company/update', [CompanyController::class, 'storeCompanyDetails'])->name('user.company.update');
    Route::get('/user/company/{companySlug}', [CompanyController::class, 'showCompanyBySlug'])->name('company.profile');

    Route::get('/get-suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestion');
    Route::get('search', [SearchController::class, 'SearchUserCompany'])->name('search');
    
    
});






Route::middleware('guest')->group(function () {

    // User Registration
    Route::get('/sign-up', [UserController::class, 'showRegistrationForm'])->name('register.form');
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


});






Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');

