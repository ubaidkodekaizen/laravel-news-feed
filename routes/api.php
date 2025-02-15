<?php 
use App\Http\Controllers\ChatController;

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Apply middleware at the route level
Route::middleware('auth:sanctum')->group(function () { 
    Route::get('/conversations', [ChatController::class, 'getConversations']);
    Route::post('/conversations/create', [ChatController::class, 'createConversation']); 
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages']);
    Route::post('/messages/send', [ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::get('conversations/{conversation}/user', [ChatController::class, 'getUserForConversation']);
    Route::post('/typing', [ChatController::class, 'userIsTyping']); 
    Route::get('/check-conversation', [ChatController::class, 'checkConversation']);

    // Add reaction to a message
    Route::post('/messages/{message}/react', [ChatController::class, 'addReaction']);
    // Remove reaction from a message
    Route::delete('/messages/{message}/react', [ChatController::class, 'removeReaction']);
});
 