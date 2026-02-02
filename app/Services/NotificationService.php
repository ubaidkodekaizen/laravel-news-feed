<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send notification to a user (sends to all their devices)
     */
    public function send($userId, $type, $title, $message, $data = [])
    {
        try {
            // Get all FCM tokens for the user
            $fcmTokens = DeviceToken::getUserTokens($userId);

            if (empty($fcmTokens)) {
                Log::info('No FCM tokens found for user', ['user_id' => $userId]);
                // Still save notification in database even if no tokens
            } else {
                // Prepare notification data
                $notificationData = array_merge([
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                ], $data);

                // Send FCM notification to all user's devices
                $results = $this->firebaseService->sendFCMNotificationToMultiple(
                    $fcmTokens,
                    $title,
                    $message,
                    $notificationData
                );

                // Remove invalid tokens
                foreach ($results as $token => $result) {
                    if ($result === false) {
                        // Token is invalid, remove it
                        DeviceToken::removeToken($userId, $token);
                        Log::info('Removed invalid FCM token', [
                            'user_id' => $userId,
                            'token' => substr($token, 0, 20) . '...'
                        ]);
                    }
                }
            }

            // Save notification in database
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);

            Log::info('Notification sent and saved', [
                'user_id' => $userId,
                'type' => $type,
                'notification_id' => $notification->id,
                'tokens_count' => count($fcmTokens)
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send post reaction notification
     */
    public function sendPostReactionNotification($postOwnerId, $reactor, $post, $reactionType)
    {
        $reactorName = trim($reactor->first_name . ' ' . $reactor->last_name);
        
        return $this->send(
            $postOwnerId,
            Notification::TYPE_POST_REACTION,
            'New Reaction',
            "{$reactorName} reacted to your post",
            [
                'post_id' => $post->id,
                'post_slug' => $post->slug,
                'reactor_id' => $reactor->id,
                'reactor_name' => $reactorName,
                'reaction_type' => $reactionType,
            ]
        );
    }

    /**
     * Send post comment notification
     */
    public function sendPostCommentNotification($postOwnerId, $commenter, $post, $comment)
    {
        $commenterName = trim($commenter->first_name . ' ' . $commenter->last_name);
        
        return $this->send(
            $postOwnerId,
            Notification::TYPE_POST_COMMENT,
            'New Comment',
            "{$commenterName} commented on your post",
            [
                'post_id' => $post->id,
                'post_slug' => $post->slug,
                'comment_id' => $comment->id,
                'commenter_id' => $commenter->id,
                'commenter_name' => $commenterName,
            ]
        );
    }

    /**
     * Send post share notification
     */
    public function sendPostShareNotification($postOwnerId, $sharer, $post)
    {
        $sharerName = trim($sharer->first_name . ' ' . $sharer->last_name);
        
        return $this->send(
            $postOwnerId,
            Notification::TYPE_POST_SHARE,
            'Post Shared',
            "{$sharerName} shared your post",
            [
                'post_id' => $post->id,
                'post_slug' => $post->slug,
                'sharer_id' => $sharer->id,
                'sharer_name' => $sharerName,
            ]
        );
    }

    /**
     * Send new message notification
     */
    public function sendNewMessageNotification($receiverId, $sender, $message, $conversationId)
    {
        $senderName = trim($sender->first_name . ' ' . $sender->last_name);
        
        return $this->send(
            $receiverId,
            Notification::TYPE_NEW_MESSAGE,
            'New Message',
            "{$senderName}: " . substr($message->content, 0, 50),
            [
                'conversation_id' => $conversationId,
                'message_id' => $message->id,
                'sender_id' => $sender->id,
                'sender_name' => $senderName,
            ]
        );
    }

    /**
     * Send message reaction notification
     */
    public function sendMessageReactionNotification($messageSenderId, $reactor, $message, $emoji)
    {
        $reactorName = trim($reactor->first_name . ' ' . $reactor->last_name);
        
        return $this->send(
            $messageSenderId,
            Notification::TYPE_MESSAGE_REACTION,
            'Message Reaction',
            "{$reactorName} reacted to your message",
            [
                'conversation_id' => $message->conversation_id,
                'message_id' => $message->id,
                'reactor_id' => $reactor->id,
                'reactor_name' => $reactorName,
                'emoji' => $emoji,
            ]
        );
    }

    /**
     * Send comment reply notification (when someone replies to a comment)
     */
    public function sendCommentReplyNotification($originalCommenterId, $replier, $post, $comment, $parentComment)
    {
        $replierName = trim($replier->first_name . ' ' . $replier->last_name);
        
        return $this->send(
            $originalCommenterId,
            Notification::TYPE_COMMENT_REPLY,
            'New Reply',
            "{$replierName} replied to your comment",
            [
                'post_id' => $post->id,
                'post_slug' => $post->slug,
                'comment_id' => $comment->id,
                'parent_comment_id' => $parentComment->id,
                'replier_id' => $replier->id,
                'replier_name' => $replierName,
            ]
        );
    }

    /**
     * Send new service notification (notify all active users when someone posts a service)
     */
    public function sendNewServiceNotification($serviceOwner, $service)
    {
        $ownerName = trim($serviceOwner->first_name . ' ' . $serviceOwner->last_name);
        
        // Get all active users except the service owner
        // Only notify users with status 'complete' to avoid spamming inactive users
        $users = User::where('id', '!=', $serviceOwner->id)
            ->where('status', 'complete')
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();

        $results = [];
        foreach ($users as $userId) {
            try {
                $results[] = $this->send(
                    $userId,
                    Notification::TYPE_NEW_SERVICE,
                    'New Service Available',
                    "{$ownerName} posted a new service: {$service->title}",
                    [
                        'service_id' => $service->id,
                        'service_title' => $service->title,
                        'owner_id' => $serviceOwner->id,
                        'owner_name' => $ownerName,
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to send service notification to user', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                // Continue with other users even if one fails
            }
        }

        Log::info('Service notification sent to users', [
            'service_id' => $service->id,
            'owner_id' => $serviceOwner->id,
            'users_notified' => count($results)
        ]);

        return $results;
    }

    /**
     * Send new product notification (notify all active users when someone posts a product)
     */
    public function sendNewProductNotification($productOwner, $product)
    {
        $ownerName = trim($productOwner->first_name . ' ' . $productOwner->last_name);
        
        // Get all active users except the product owner
        // Only notify users with status 'complete' to avoid spamming inactive users
        $users = User::where('id', '!=', $productOwner->id)
            ->where('status', 'complete')
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();

        $results = [];
        foreach ($users as $userId) {
            try {
                $results[] = $this->send(
                    $userId,
                    Notification::TYPE_NEW_PRODUCT,
                    'New Product Available',
                    "{$ownerName} posted a new product: {$product->title}",
                    [
                        'product_id' => $product->id,
                        'product_title' => $product->title,
                        'owner_id' => $productOwner->id,
                        'owner_name' => $ownerName,
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to send product notification to user', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                // Continue with other users even if one fails
            }
        }

        Log::info('Product notification sent to users', [
            'product_id' => $product->id,
            'owner_id' => $productOwner->id,
            'users_notified' => count($results)
        ]);

        return $results;
    }
}
