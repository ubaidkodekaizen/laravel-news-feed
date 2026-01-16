<?php

namespace App\Helpers;
use App\Models\Content\Blog;
use App\Models\Business\Company;
use App\Models\Content\Event;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\Business\Subscription;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\Business\Plan;
use App\Models\Business\ProductService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GeneralHelper
{

    public static function getUserCount(): int
    {
        return User::count();
    }

    public static function getSubscriberCount(): int
    {
        return Subscription::whereIn('subscription_type', ['Monthly', 'Yearly'])->count();
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
