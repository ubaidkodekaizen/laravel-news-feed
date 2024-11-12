<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');

Route::get('user/company/details', [UserController::class, 'showUserCompanyForm'])->name('user.company.details');
Route::post('/user/company/update', [UserController::class, 'storeCompanyDetails'])->name('user.company.update');

Route::get('/search', action: function () {
    return view('user.search');
})->name('search');


// User Registration
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [UserController::class, 'register'])->name('register');

// User Login
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');

// Forgot Password
Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('forgotPassword.form');
Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

