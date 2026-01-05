<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Chat\Conversation;
use App\Models\Chat\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\UserOnlineService;
use App\Jobs\SendOfflineMessageNotification;
use App\Traits\FormatsUserData;
use App\Services\FirebaseService;

class ChatController extends Controller
{
    use AuthorizesRequests, FormatsUserData;

       protected $userOnlineService;
    protected $firebaseService; // ✅ ADD THIS

    public function __construct(
        UserOnlineService $userOnlineService,
        FirebaseService $firebaseService // ✅ ADD THIS
    ) {
        $this->userOnlineService = $userOnlineService;
        $this->firebaseService = $firebaseService; // ✅ ADD THIS
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->user_id;

        $conversation = Conversation::firstOrCreate(
            [
                'user_one_id' => min($senderId, $receiverId),
                'user_two_id' => max($senderId, $receiverId)
            ],
            ['last_message_at' => now()]
        );

        // Format dates properly
        return response()->json([
            'id' => $conversation->id,
            'user_one_id' => $conversation->user_one_id,
            'user_two_id' => $conversation->user_two_id,
            'last_message_at' => $conversation->last_message_at ? $conversation->last_message_at->toIso8601String() : null,
            'created_at' => $conversation->created_at ? $conversation->created_at->toIso8601String() : null,
            'updated_at' => $conversation->updated_at ? $conversation->updated_at->toIso8601String() : null,
        ]);
    }

    public function getUserForConversation(Conversation $conversation)
    {
        $user = $conversation->user_one_id === auth()->id()
            ? $conversation->userTwo
            : $conversation->userOne;

        if ($user) {
            return response()->json($this->formatUserData($user));
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function userIsTyping(Request $request)
    {
        try {
            $user = auth()->user();
            $conversationId = $request->input('conversation_id');

            if (!$conversationId) {
                return response()->json(['error' => 'Conversation ID is required'], 400);
            }

            $this->firebaseService->updateTypingStatus($conversationId, $user->id, $user, true);

            \Log::info('User is typing...', [
                'user_id' => $user->id,
                'conversation_id' => $conversationId
            ]);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ],
                'conversation_id' => $conversationId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in userIsTyping:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function getMessages(Conversation $conversation)
    {
        try {
            $this->authorize('view-conversation', $conversation);

            $messages = Message::where('conversation_id', $conversation->id)
                ->with(['sender:id,first_name,last_name,email,photo,slug', 'reactions.user:id,first_name,last_name'])
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    // Format sender data using trait
                    $senderData = $this->formatUserData($message->sender);
                    $message->sender->user_has_photo = $senderData['user_has_photo'];
                    $message->sender->user_initials = $senderData['user_initials'];
                    $message->sender->photo = $senderData['photo'];

                    // Format dates properly, handling null values
                    return [
                        'id' => $message->id,
                        'conversation_id' => $message->conversation_id,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id,
                        'content' => $message->content,
                        'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                        'created_at' => $message->created_at ? $message->created_at->toIso8601String() : null,
                        'updated_at' => $message->updated_at ? $message->updated_at->toIso8601String() : null,
                        'sender' => $message->sender,
                        'reactions' => $message->relationLoaded('reactions') && $message->reactions 
                            ? $message->reactions->map(function ($reaction) {
                                return [
                                    'id' => $reaction->id,
                                    'user_id' => $reaction->user_id,
                                    'emoji' => $reaction->emoji, // Message reactions use emoji field
                                    'user' => $reaction->relationLoaded('user') && $reaction->user ? [
                                        'id' => $reaction->user->id,
                                        'first_name' => $reaction->user->first_name,
                                        'last_name' => $reaction->user->last_name,
                                    ] : null,
                                ];
                            })->values()
                            : [],
                    ];
                });

            // Mark unread messages as read
            Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Error in getMessages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error fetching messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getConversations(): JsonResponse
    {
        try {
            $user = request()->user();
            $userId = $user->id;

            $conversations = Conversation::where(function ($query) use ($userId) {
                $query->where('user_one_id', $userId)
                    ->orWhere('user_two_id', $userId);
            })
                ->with(['userOne', 'userTwo'])
                ->withCount(['messages as unread_count' => function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->whereNull('read_at');
                }])
                ->get()
                ->map(function ($conversation) use ($userId) {
                    $otherUser = $conversation->user_one_id === $userId
                        ? $conversation->userTwo
                        : $conversation->userOne;

                    if (!$otherUser) {
                        return null;
                    }

                    $lastMessage = $conversation->messages()->latest()->first();

                    // Helper function to safely format dates
                    $formatDate = function ($date) {
                        if (!$date) {
                            return null;
                        }
                        try {
                            if ($date instanceof \Carbon\Carbon || $date instanceof \DateTime) {
                                return $date->toIso8601String();
                            }
                            if (is_string($date)) {
                                return \Carbon\Carbon::parse($date)->toIso8601String();
                            }
                            return null;
                        } catch (\Exception $e) {
                            return null;
                        }
                    };

                    return [
                        'id' => $conversation->id,
                        'user' => $this->formatUserData($otherUser),
                        'last_message' => $lastMessage ? [
                            'id' => $lastMessage->id,
                            'conversation_id' => $lastMessage->conversation_id,
                            'sender_id' => $lastMessage->sender_id,
                            'receiver_id' => $lastMessage->receiver_id,
                            'content' => $lastMessage->content,
                            'read_at' => $formatDate($lastMessage->read_at),
                            'created_at' => $formatDate($lastMessage->created_at),
                            'updated_at' => $formatDate($lastMessage->updated_at),
                        ] : null,
                        'last_message_at' => $formatDate($conversation->last_message_at),
                        'unread_count' => $conversation->unread_count,
                    ];
                })
                ->filter(); // Remove null entries

            return response()->json($conversations->values());
        } catch (\Exception $e) {
            \Log::error('Error fetching conversations: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to fetch conversations',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        return \DB::transaction(function () use ($senderId, $receiverId, $request) {
            try {
                $conversation = Conversation::firstOrCreate(
                    [
                        'user_one_id' => min($senderId, $receiverId),
                        'user_two_id' => max($senderId, $receiverId)
                    ],
                    ['last_message_at' => now()]
                );

                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'content' => $request->content,
                ]);

                $message->load('sender:id,first_name,last_name,email,photo,slug');

                $conversation->update(['last_message_at' => now()]);

                $this->firebaseService->sendMessage($conversation->id, $message);
                \Log::info('Message broadcasted:', ['message' => $message]);

                // Check if receiver is offline
                if (!$this->userOnlineService->isUserOnline($receiverId)) {
                    $receiver = User::find($receiverId);
                    $sender = auth()->user();
                    SendOfflineMessageNotification::dispatch($sender, $receiver, $message);
                    \Log::info('Offline message email notification queued', [
                        'receiver_id' => $receiverId,
                        'sender_id' => $senderId
                    ]);
                }

                return response()->json([
                    'id' => $message->id,
                    'conversation_id' => $message->conversation_id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                    'created_at' => $message->created_at ? $message->created_at->toIso8601String() : null,
                    'updated_at' => $message->updated_at ? $message->updated_at->toIso8601String() : null,
                    'sender' => $this->formatUserData($message->sender),
                ]);
            } catch (\Exception $e) {
                \Log::error('Message send failed: ' . $e->getMessage());
                return response()->json(['error' => 'Message sending failed'], 500);
            }
        });
    }

    public function checkConversation(Request $request)
    {
        $receiverId = $request->input('receiver_id');
        $userId = auth()->id();

        $conversationExists = Conversation::where(function ($query) use ($userId, $receiverId) {
            $query->where('user_one_id', $userId)
                ->where('user_two_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('user_one_id', $receiverId)
                ->where('user_two_id', $userId);
        })->exists();

        $receiver = null;

        if (!$conversationExists) {
            $receiver = User::find($receiverId);
        }

        return response()->json([
            'conversation_exists' => $conversationExists,
            'receiver' => $receiver
        ]);
    }

    public function addReaction(Message $message, Request $request)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $user = auth()->user();

        $existingReaction = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();

        if ($existingReaction) {
            return response()->json(['message' => 'You have already reacted with this emoji.'], 400);
        }

        $reaction = $message->reactions()->create([
            'user_id' => $user->id,
            'emoji' => $request->emoji,
        ]);

        $reaction->load('user:id,first_name,last_name');

        // ✅ ADD THESE LINES
        $this->firebaseService->addReaction(
            $message->conversation_id,
            $message->id,
            $reaction
        );

        return response()->json($reaction);
    }

    public function removeReaction(Message $message, Request $request)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        $user = auth()->user();

        $reaction = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found.'], 404);
        }



        $reactionId = $reaction->id;
        $reaction->delete();

        // ✅ ADD THESE LINES
        $this->firebaseService->removeReaction(
            $message->conversation_id,
            $message->id,
            $reactionId
        );

        return response()->json(['message' => 'Reaction removed.']);
    }
}
