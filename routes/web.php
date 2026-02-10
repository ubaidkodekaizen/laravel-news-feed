<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\FeedController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\FeedController as AdminFeedController;
use App\Http\Controllers\API\UserController as APIUserController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/sign-up', [UserController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [UserController::class, 'register'])->name('register');

    // Login
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

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
    // News Feed
    Route::get('/news-feed', [FeedController::class, 'index'])->name('news-feed');

    // News Feed API Routes (for AJAX calls)
    Route::prefix('news-feed')->group(function () {
        // Post CRUD
        Route::get('/posts', [FeedController::class, 'getFeed'])->name('feed.posts');
        Route::get('/posts/{id}/data', [FeedController::class, 'getPostData'])->name('feed.posts.data');
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

        // Single post detail page (must be LAST to avoid conflicts)
        Route::get('/posts/{slug}', [FeedController::class, 'showPostPage'])
            ->where('slug', '[a-z0-9\-]+')
            ->name('feed.post.page');
    });

    // Report Routes
    Route::post('/report/user', [ReportController::class, 'reportUser'])->name('report.user');
    Route::post('/report/post', [ReportController::class, 'reportPost'])->name('report.post');

    // User Profile
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug'])->name('user.profile');
    Route::get('/user/details', [UserController::class, 'showUserDetailsForm'])->name('user.details.show');
    Route::post('/user/details/update', [UserController::class, 'updateUserDetails'])->name('user.details.update');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', RoleMiddleware::class . ':1|2|3'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Users Management
    Route::get('/admin/users', [AdminUserController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/add', [AdminUserController::class, 'addUser'])->name('admin.add.user');
    Route::post('/admin/users/create', [AdminUserController::class, 'createUser'])->name('admin.create.user');
    Route::get('/admin/user/profile/{id}', [AdminUserController::class, 'showUserById'])->name('admin.user.profile');
    Route::get('/admin/user/edit/{id}', [AdminUserController::class, 'editUser'])->name('admin.user.edit');
    Route::post('/admin/user/update', [AdminUserController::class, 'updateUserDetails'])->name('admin.user.update');
    Route::delete('/admin/delete-user/{id}', [AdminUserController::class, 'deleteUser'])->name('admin.delete.user');
    Route::post('/admin/restore-user/{id}', [AdminUserController::class, 'restoreUser'])->name('admin.restore.user');

    // Feed Management
    Route::get('/admin/feed', [AdminFeedController::class, 'index'])->name('admin.feed');
    Route::get('/admin/feed/{id}', [AdminFeedController::class, 'show'])->name('admin.feed.show');
    Route::delete('/admin/feed/post/{id}', [AdminFeedController::class, 'deletePost'])->name('admin.feed.post.delete');
    Route::post('/admin/feed/post/{id}/restore', [AdminFeedController::class, 'restorePost'])->name('admin.feed.post.restore');
    Route::delete('/admin/feed/comment/{id}', [AdminFeedController::class, 'deleteComment'])->name('admin.feed.comment.delete');
    Route::post('/admin/feed/comment/{id}/restore', [AdminFeedController::class, 'restoreComment'])->name('admin.feed.comment.restore');
});

/*
|--------------------------------------------------------------------------
| Logout Routes
|--------------------------------------------------------------------------
*/

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login.form');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/logout', function () {
        Auth::logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');
});
