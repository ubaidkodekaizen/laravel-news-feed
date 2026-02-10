<?php

namespace App\Helpers;

use App\Models\Users\User;
use Carbon\Carbon;

class GeneralHelper
{
    public static function getUserCount(): int
    {
        return User::where('role_id', 4)->whereNull('deleted_at')->count();
    }

    public static function defaultAvatar(): string
    {
        return 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
    }

    public static function formatDate($date, $format = 'M d, Y'): string
    {
        return Carbon::parse($date)->format($format);
    }

    public static function timeAgo($date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }
}
