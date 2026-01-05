<?php

namespace App\Services;

use App\Services\FirebaseService;

class UserOnlineService
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Check if a user is currently online via Firebase
     */
    public function isUserOnline(int $userId): bool
    {
        return $this->firebaseService->isUserOnline($userId);
    }

    /**
     * Mark user as active
     */
    public function markUserActive(int $userId): void
    {
        $this->firebaseService->updateUserOnlineStatus($userId, true);
    }
}
