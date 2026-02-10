<?php

namespace App\Services;

use App\Models\Notifications\Notification;
use App\Models\Users\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a user (saves to database only - no push notifications)
     */
    public function send($userId, $type, $title, $message, $data = [])
    {
        try {
            // Save notification in database
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);

            Log::info('Notification saved', [
                'user_id' => $userId,
                'type' => $type,
                'notification_id' => $notification->id,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to save notification', [
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
}
