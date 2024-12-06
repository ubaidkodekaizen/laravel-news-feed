<?php

namespace App\Helpers;
use App\Models\Company;
use App\Models\User;
use App\Models\Plan;
use App\Models\ProductService;

class GeneralHelper
{

    public static function getUserCount(): int
    {
        return User::count();
    }



}
