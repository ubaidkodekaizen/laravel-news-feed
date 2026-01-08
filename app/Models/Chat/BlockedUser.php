<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BlockedUser extends Model
{
    protected $fillable = [
        'blocker_id',
        'blocked_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The user who blocked
     */
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    /**
     * The user who was blocked
     */
    public function blocked()
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }

    /**
     * Check if a user has blocked another user
     */
    public static function isBlocked($blockerId, $blockedId): bool
    {
        return self::where('blocker_id', $blockerId)
            ->where('blocked_id', $blockedId)
            ->exists();
    }

    /**
     * Check if there's a block between two users (either direction)
     */
    public static function isBlockedBetween($userId1, $userId2): bool
    {
        return self::where(function ($query) use ($userId1, $userId2) {
            $query->where('blocker_id', $userId1)
                ->where('blocked_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('blocker_id', $userId2)
                ->where('blocked_id', $userId1);
        })->exists();
    }
}
