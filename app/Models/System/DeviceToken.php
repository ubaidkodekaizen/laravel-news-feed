<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Users\User;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fcm_token',
        'device_type',
        'device_id',
        'device_name',
    ];

    /**
     * Get the user that owns the device token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register or update a device token for a user.
     */
    public static function registerToken($userId, $fcmToken, $deviceType = null, $deviceId = null, $deviceName = null)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'fcm_token' => $fcmToken,
            ],
            [
                'device_type' => $deviceType,
                'device_id' => $deviceId,
                'device_name' => $deviceName,
            ]
        );
    }

    /**
     * Remove a device token.
     */
    public static function removeToken($userId, $fcmToken)
    {
        return self::where('user_id', $userId)
            ->where('fcm_token', $fcmToken)
            ->delete();
    }

    /**
     * Get all tokens for a user.
     */
    public static function getUserTokens($userId)
    {
        return self::where('user_id', $userId)
            ->pluck('fcm_token')
            ->toArray();
    }
}
