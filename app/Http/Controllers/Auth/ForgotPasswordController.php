<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\CanResetPassword; // Change to the correct namespace
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use CanResetPassword;

    public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }
}
