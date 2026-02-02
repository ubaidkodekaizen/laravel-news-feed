<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Notification types
    const TYPE_POST_REACTION = 'post_reaction';
    const TYPE_POST_COMMENT = 'post_comment';
    const TYPE_COMMENT_REPLY = 'comment_reply';
    const TYPE_POST_SHARE = 'post_share';
    const TYPE_NEW_MESSAGE = 'new_message';
    const TYPE_MESSAGE_REACTION = 'message_reaction';
    const TYPE_NEW_SERVICE = 'new_service';
    const TYPE_NEW_PRODUCT = 'new_product';
    const TYPE_PROFILE_VIEW = 'profile_view';
    const TYPE_NEW_FOLLOWER = 'new_follower';
    const TYPE_SUBSCRIPTION_EVENT = 'subscription_event';
    const TYPE_ADMIN_NOTIFICATION = 'admin_notification';

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }
}
