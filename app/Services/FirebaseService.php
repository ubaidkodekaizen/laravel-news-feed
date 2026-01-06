<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Auth;

class FirebaseService
{
    protected $database;
    protected $auth;

    public function __construct()
    {
        try {
            $credentialsPath = config('services.firebase.credentials');
            $databaseUrl = config('services.firebase.database_url');

            if (empty($credentialsPath)) {
                throw new \Exception('Firebase credentials path is not configured. Please set FIREBASE_CREDENTIALS in .env');
            }

            // If path contains placeholder text or is empty, use default location
            if (empty($credentialsPath) || str_contains($credentialsPath, '/full/absolute/path/to/')) {
                $credentialsPath = storage_path('app/firebase-credentials.json');
            }

            if (!file_exists($credentialsPath)) {
                throw new \Exception("Firebase credentials file not found at: {$credentialsPath}. Please check your FIREBASE_CREDENTIALS setting in .env");
            }

            if (empty($databaseUrl)) {
                throw new \Exception('Firebase database URL is not configured. Please set FIREBASE_DATABASE_URL in .env');
            }

            $factory = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUrl);

            $this->database = $factory->createDatabase();
            $this->auth = $factory->createAuth();
        } catch (\Exception $e) {
            \Log::error('FirebaseService initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send message to Firebase Realtime Database
     */
    public function sendMessage($conversationId, $message)
    {
        $reference = $this->database->getReference('messages/' . $conversationId);

        // ✅ Generate full photo URL
        $photoUrl = null;
        if (!empty($message->sender->photo)) {
            // Check if photo already has full URL
            // Use helper function that handles both S3 URLs and local storage
            $photoUrl = getImageUrl($message->sender->photo);
        }

        $messageData = [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'content' => $message->content,
            'created_at' => $message->created_at->toIso8601String(),
            'read_at' => $message->read_at?->toIso8601String(),
            'sender' => [
                'id' => $message->sender->id,
                'first_name' => $message->sender->first_name,
                'last_name' => $message->sender->last_name,
                'email' => $message->sender->email,
                'photo' => $photoUrl, // ✅ Full URL
                'slug' => $message->sender->slug,
                'user_has_photo' => !empty($message->sender->photo),
                'user_initials' => strtoupper(
                    substr($message->sender->first_name, 0, 1) .
                        substr($message->sender->last_name, 0, 1)
                ),
            ],
            'reactions' => [],
        ];

        return $reference->getChild((string)$message->id)->set($messageData);
    }

    /**
     * Update message in Firebase
     */
    public function updateMessage(int $conversationId, int $messageId, array $data): void
    {
        try {
            $reference = $this->database->getReference("messages/{$conversationId}/{$messageId}");
            $reference->update($data);

            \Log::info('Message updated in Firebase', [
                'conversation_id' => $conversationId,
                'message_id' => $messageId,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update message in Firebase: ' . $e->getMessage(), [
                'conversation_id' => $conversationId,
                'message_id' => $messageId
            ]);
            throw $e;
        }
    }

    /**
     * Delete message from Firebase
     */
    public function deleteMessage(int $conversationId, int $messageId): void
    {
        try {
            $reference = $this->database->getReference("messages/{$conversationId}/{$messageId}");
            $reference->remove();

            \Log::info('Message deleted from Firebase', [
                'conversation_id' => $conversationId,
                'message_id' => $messageId
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete message from Firebase: ' . $e->getMessage(), [
                'conversation_id' => $conversationId,
                'message_id' => $messageId
            ]);
            throw $e;
        }
    }

    /**
     * Add reaction to message in Firebase
     */
    public function addReaction($conversationId, $messageId, $reaction)
    {
        $reference = $this->database->getReference(
            "messages/{$conversationId}/{$messageId}/reactions/{$reaction->id}"
        );

        return $reference->set([
            'id' => $reaction->id,
            'user_id' => $reaction->user_id,
            'emoji' => $reaction->emoji,
            'user' => [
                'id' => $reaction->user->id,
                'first_name' => $reaction->user->first_name,
                'last_name' => $reaction->user->last_name,
            ],
        ]);
    }

    /**
     * Remove reaction from Firebase
     */
    public function removeReaction($conversationId, $messageId, $reactionId)
    {
        $reference = $this->database->getReference(
            "messages/{$conversationId}/{$messageId}/reactions/{$reactionId}"
        );

        return $reference->remove();
    }

    /**
     * Update typing status in Firebase
     */
    public function updateTypingStatus($conversationId, $userId, $user, $isTyping = true)
    {
        $reference = $this->database->getReference('typing/' . $conversationId . '/' . $userId);

        if ($isTyping) {
            return $reference->set([
                'user_id' => $userId,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'timestamp' => time(),
            ]);
        } else {
            return $reference->remove();
        }
    }

    /**
     * Update user online status
     */
    public function updateUserOnlineStatus($userId, $isOnline = true)
    {
        $reference = $this->database->getReference('presence/users/' . $userId);

        if ($isOnline) {
            $reference->set([
                'online' => true,
                'last_active' => time(),
            ]);

            $reference->onDisconnect()->set([
                'online' => false,
                'last_active' => time(),
            ]);
        } else {
            $reference->set([
                'online' => false,
                'last_active' => time(),
            ]);
        }
    }

    /**
     * Create custom token for Firebase Authentication
     */
    public function createCustomToken($userId)
    {
        try {
            $token = $this->auth->createCustomToken((string) $userId);

            // ✅ Convert token object to string
            $tokenString = $token->toString();

            \Log::info('Firebase token created', [
                'user_id' => $userId,
                'token_length' => strlen($tokenString),
            ]);

            return $tokenString;
        } catch (\Exception $e) {
            \Log::error('Firebase custom token creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if user is online via Firebase
     */
    public function isUserOnline($userId)
    {
        try {
            $snapshot = $this->database->getReference('presence/users/' . $userId)->getSnapshot();

            if (!$snapshot->exists()) {
                return false;
            }

            $presence = $snapshot->getValue();
            return $presence['online'] ?? false;
        } catch (\Exception $e) {
            \Log::error('Error checking online status: ' . $e->getMessage());
            return false;
        }
    }
}
