<?php 
use App\Http\Controllers\ChatController;

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        app(App\Services\UserOnlineService::class)->markUserActive(auth()->id());
        return response()->json(['status' => 'success']);
    })->name('user.ping');
    
    Route::post('/user/offline', function (Request $request) {
        Redis::del('user:last_active:'.auth()->id());
        return response()->json(['status' => 'success']);
    })->name('user.offline');
});
 