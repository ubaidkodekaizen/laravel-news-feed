<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\NewMessageEvent;
use App\Events\UserTyping;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Jobs\BroadcastMessage;
use App\Jobs\UserTypingJob;
use App\Services\UserOnlineService;
use App\Jobs\SendOfflineMessageNotification;

class ChatController extends Controller
{
    use AuthorizesRequests;

    protected $userOnlineService;

    public function __construct(UserOnlineService $userOnlineService)
    {
        $this->userOnlineService = $userOnlineService;
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

        return response()->json($conversation);
    }



    public function getUserForConversation(Conversation $conversation)
    {
        $user = $conversation->user_one_id === auth()->id()
            ? $conversation->userTwo
            : $conversation->userOne;

        if ($user) {
            $photoPath = $user->photo ?? null;
            $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists('profile_photos/' . basename($photoPath));
            $initials = strtoupper(
                substr($user->first_name, 0, 1) .
                    substr($user->last_name ?? '', 0, 1)
            );

            $photoUrl = $hasPhoto
                ? (str_starts_with($photoPath, 'http')
                    ? $photoPath
                    : asset('storage/profile_photos/' . basename($photoPath)))
                : null;

            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'slug' => $user->slug,
                'photo' => $photoUrl,
                'user_has_photo' => $hasPhoto,
                'user_initials' => $initials,
            ]);
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

            // Dispatch the job to broadcast the typing event
            UserTypingJob::dispatch($conversationId, $user);

            // Log the event for debugging
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
                    $sender = $message->sender;
                    $photoPath = $sender->photo ?? null;

                    // Check if photo exists
                    $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists('profile_photos/' . basename($photoPath));

                    // Generate initials
                    $initials = strtoupper(
                        substr($sender->first_name, 0, 1) .
                            substr($sender->last_name ?? '', 0, 1)
                    );

                    // Add computed properties
                    $sender->user_has_photo = $hasPhoto;
                    $sender->user_initials = $initials;

                    // Set photo URL only if it exists
                    $sender->photo = $hasPhoto
                        ? (str_starts_with($photoPath, 'http')
                            ? $photoPath
                            : asset('storage/profile_photos/' . basename($photoPath)))
                        : null;

                    return $message;
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

                $photoPath = $otherUser->photo ?? null;
                $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists('profile_photos/' . basename($photoPath));
                $initials = strtoupper(
                    substr($otherUser->first_name, 0, 1) .
                        substr($otherUser->last_name ?? '', 0, 1)
                );

                $photoUrl = $hasPhoto
                    ? (str_starts_with($photoPath, 'http')
                        ? $photoPath
                        : asset('storage/profile_photos/' . basename($photoPath)))
                    : null;

                return [
                    'id' => $conversation->id,
                    'user' => [
                        'id' => $otherUser->id,
                        'first_name' => $otherUser->first_name,
                        'last_name' => $otherUser->last_name,
                        'email' => $otherUser->email,
                        'photo' => $photoUrl,
                        'user_has_photo' => $hasPhoto,
                        'user_initials' => $initials,
                    ],
                    'last_message' => $conversation->messages()->latest()->first(),
                    'unread_count' => $conversation->unread_count,
                ];
            });

        return response()->json($conversations);
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

                // Eager load sender details
                $message->load('sender:id,first_name,last_name,email,photo');

                // Format sender's photo URL
                $sender = $message->sender;
                $photoPath = $sender->photo ?? null;

                // Check if photo exists
                $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists('profile_photos/' . basename($photoPath));

                // Generate initials
                $initials = strtoupper(
                    substr($sender->first_name, 0, 1) .
                        substr($sender->last_name ?? '', 0, 1)
                );

                $sender->user_has_photo = $hasPhoto;
                $sender->user_initials = $initials;
                $sender->photo = $hasPhoto
                    ? (str_starts_with($photoPath, 'http')
                        ? $photoPath
                        : asset('storage/profile_photos/' . basename($photoPath)))
                    : null;

                $conversation->update(['last_message_at' => now()]);

                BroadcastMessage::dispatch($message);
                \Log::info('Message broadcasted:', ['message' => $message]);


                // Check if receiver is offline
                if (!$this->userOnlineService->isUserOnline($receiverId)) {
                    // Get receiver
                    $receiver = User::find($receiverId);
                    $sender = auth()->user();

                    // Dispatch job to send email notification
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
                    'read_at' => $message->read_at,
                    'created_at' => $message->created_at,
                    'updated_at' => $message->updated_at,
                    'sender' => [
                        'id' => $message->sender->id,
                        'first_name' => $message->sender->first_name,
                        'last_name' => $message->sender->last_name,
                        'email' => $message->sender->email,
                        'photo' => $message->sender->photo,
                    ]
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

        $receiver = null; // Default value

        if (!$conversationExists) {
            // Fetch receiver's user data
            $receiver = User::find($receiverId);
        }

        return response()->json([
            'conversation_exists' => $conversationExists,
            'receiver' => $receiver // Return receiver data if conversation doesn't exist
        ]);
    }

    public function addReaction(Message $message, Request $request)
    {
        $request->validate([
            'emoji' => 'required|string|max:10', // Validate the emoji
        ]);

        $user = auth()->user();

        // Check if the user has already reacted with this emoji
        $existingReaction = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();

        if ($existingReaction) {
            return response()->json(['message' => 'You have already reacted with this emoji.'], 400);
        }

        // Add the reaction
        $reaction = $message->reactions()->create([
            'user_id' => $user->id,
            'emoji' => $request->emoji,
        ]);

        return response()->json($reaction);
    }


    public function removeReaction(Message $message, Request $request)
    {
        $request->validate([
            'emoji' => 'required|string|max:10', // Validate the emoji
        ]);

        $user = auth()->user();

        // Find and delete the reaction
        $reaction = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();

        if (!$reaction) {
            return response()->json(['message' => 'Reaction not found.'], 404);
        }

        $reaction->delete();

        return response()->json(['message' => 'Reaction removed.']);
    }
}
