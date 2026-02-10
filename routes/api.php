<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FeedController;
use App\Http\Controllers\API\ReportController;

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/register', [UserController::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [UserController::class, 'login'])->middleware('throttle:5,1');
Route::post('/forget-password', [UserController::class, 'sendResetLink'])->middleware('throttle:3,1');

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [UserController::class, 'logout'])->name('api.logout');

    /*
    |--------------------------------------------------------------------------
    | Report Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/report/user', [ReportController::class, 'reportUser'])->name('api.report.user');
    Route::post('/report/post', [ReportController::class, 'reportPost'])->name('api.report.post');

    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/user/update/personal', [UserController::class, 'updatePersonal']);
    Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug']);
    Route::delete('/user/delete', [UserController::class, 'deleteUser']);

    /*
    |--------------------------------------------------------------------------
    | News Feed Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/feed/posts', [FeedController::class, 'getFeed'])->name('api.feed.posts');
    Route::get('/feed/posts/{slug}', [FeedController::class, 'getPost'])->name('api.feed.post.show');
    Route::post('/feed/posts', [FeedController::class, 'createPost'])->name('api.feed.post.create');
    Route::put('/feed/posts/{slug}', [FeedController::class, 'updatePost'])->name('api.feed.post.update');
    Route::delete('/feed/posts/{slug}', [FeedController::class, 'deletePost'])->name('api.feed.post.delete');
    Route::get('/feed/reactions/types', [FeedController::class, 'getReactionTypes'])->name('api.feed.reactions.types');
    Route::post('/feed/reactions', [FeedController::class, 'addReaction'])->name('api.feed.reaction.add');
    Route::delete('/feed/reactions', [FeedController::class, 'removeReaction'])->name('api.feed.reaction.remove');
    Route::get('/feed/posts/{postId}/reactions-count', [FeedController::class, 'getReactionCount'])->name('api.feed.post.reactions.count');
    Route::get('/feed/posts/{postId}/reactions-list', [FeedController::class, 'getReactionsList'])->name('api.feed.post.reactions.list');
    Route::post('/feed/posts/{postId}/comments', [FeedController::class, 'addComment'])->name('api.feed.comment.add');
    Route::put('/feed/comments/{commentId}', [FeedController::class, 'updateComment'])->name('api.feed.comment.update');
    Route::delete('/feed/comments/{commentId}', [FeedController::class, 'deleteComment'])->name('api.feed.comment.delete');
    Route::get('/feed/posts/{postId}/comments', [FeedController::class, 'getComments'])->name('api.feed.comments');
    Route::get('/feed/posts/{postId}/comments-count', [FeedController::class, 'getCommentCount'])->name('api.feed.post.comments.count');
    Route::post('/feed/posts/{postId}/share', [FeedController::class, 'sharePost'])->name('api.feed.post.share');
    Route::get('/feed/posts/{postId}/shares-list', [FeedController::class, 'getSharesList'])->name('api.feed.post.shares.list');
    Route::get('/feed/user/{userId?}/posts', [FeedController::class, 'getUserPosts'])->name('api.feed.user.posts');
});
