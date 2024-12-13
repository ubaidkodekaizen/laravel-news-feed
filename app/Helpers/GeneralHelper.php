<?php

namespace App\Helpers;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Event;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use App\Models\ProductService;

class GeneralHelper
{

    public static function getUserCount(): int
    {
        return User::count();
    }

    public static function getSubscriberCount(): int
    {
        return Subscription::count();
    }

    public static function getBlogCount(): int
    {
        return Blog::count();
    }

    public static function getEventCount(): int
    {
        return Event::count();
    }



}
