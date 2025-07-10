<?php
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\EducationController;


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
    Redis::del('user:last_active:' . auth()->id());
    return response()->json(['status' => 'success']);
  })->name('user.offline');

  // Mobile API Routes

  Route::get('/user/profile/{slug}', [UserController::class, 'showUserBySlug']);
  Route::delete('/user/delete', [UserController::class, 'deleteUser']);

  Route::get('/user/products', [ProductController::class, 'apiIndex']);
  Route::get('/user/products/{id}', [ProductController::class, 'apiShow']);
  Route::post('/user/products/store/{id?}', [ProductController::class, 'apiStore']);
  Route::delete('/user/products/delete/{id}', [ProductController::class, 'apiDelete']);

  Route::get('/user/services', [ServiceController::class, 'apiIndex']);
  Route::get('/user/services/{id}', [ServiceController::class, 'apiShow']);
  Route::post('/user/services/store/{id?}', [ServiceController::class, 'apiStore']);
  Route::delete('/user/services/delete/{id}', [ServiceController::class, 'apiDelete']);

  Route::get('/user/qualifications', [EducationController::class, 'apiIndex']);
  Route::get('/user/qualifications/{id}', [EducationController::class, 'apiShow']);
  Route::post('/user/qualifications/store/{id?}', [EducationController::class, 'apiStore']);
  Route::delete('/user/qualifications/delete/{id}', [EducationController::class, 'apiDelete']);

  Route::get('/industries', [PageController::class, 'getIndustries']);
  Route::get('/industry-experts/{industry}', [PageController::class, 'getIndustryExperts']);

  Route::get('/products', [PageController::class, 'getProducts']);
  Route::get('/services', [PageController::class, 'getServices']);

  Route::get('/search-filters', [SearchController::class, 'getDropdownFilters']);
  Route::get('/search', [SearchController::class, 'searchUserCompany']);
  Route::get('/get-suggestions', [SearchController::class, 'getSuggestions']);

  Route::post('/subscribe/iap', [UserController::class, 'handleIapSubscription']);

});
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/forget-password', [UserController::class, 'sendResetLink']);


