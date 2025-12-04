<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

class UserOnlineService
{
    /**
     * Check if a user is currently online
     * 
     * @param int $userId
     * @return bool
     */
    public function isUserOnline(int $userId): bool
    {
        // Method 1: Check if user has an active websocket connection
        // This uses Laravel Reverb's presence channels
        if (app()->environment('production')) {
            return $this->checkPresenceChannel($userId);
        }
        
        // Method 2: Check if user has been active in the last 5 minutes
        // As a fallback, check the last active timestamp in Redis
        return $this->checkLastActive($userId);
    }
    
    /**
     * Check if user is in presence channel
     */
    private function checkPresenceChannel(int $userId): bool
    {
        try {
            // Check if user is in any presence channel
            // This depends on your Reverb/Pusher setup
            $isOnline = Redis::exists('presence:user-online:'.$userId);
            return (bool) $isOnline;
        } catch (\Exception $e) {
            \Log::error('Error checking presence channel: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if user was active in the last 5 minutes
     */
    private function checkLastActive(int $userId): bool
    {
        try {
            $lastActive = Redis::get('user:last_active:'.$userId);
            
            if (!$lastActive) {
                return false;
            }
            
            // Consider user online if active in the last 5 minutes
            $fiveMinutesAgo = now()->subMinutes(5)->timestamp;
            return (int) $lastActive > $fiveMinutesAgo;
        } catch (\Exception $e) {
            \Log::error('Error checking last active: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark a user as active now
     */
    public function markUserActive(int $userId): void
    {
        try {
            Redis::set('user:last_active:'.$userId, now()->timestamp);
            Redis::expire('user:last_active:'.$userId, 60 * 60); // Expire after 1 hour
        } catch (\Exception $e) {
            \Log::error('Error marking user active: ' . $e->getMessage());
        }
    }
}