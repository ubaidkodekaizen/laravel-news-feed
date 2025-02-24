<?php

namespace App\Helpers;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\Plan;
use App\Models\ProductService;
use Illuminate\Support\Facades\Auth;

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

    public static function getProductCountbyAuth(): int
    {
        return Product::where('user_id', Auth::id())->count();
    }

    public static function getServiceCountbyAuth(): int
    {
        return Service::where('user_id', Auth::id())->count();
    }



}
