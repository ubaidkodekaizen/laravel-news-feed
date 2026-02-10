<?php

namespace App\Helpers;

use App\Models\Users\User;

class GeneralHelper
{
    /**
     * Get count of regular users (role_id = 4)
     */
    public static function getUserCount(): int
    {
        return User::where('role_id', 4)->whereNull('deleted_at')->count();
    }
}
