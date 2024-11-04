<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\CanResetPassword; // Change to the correct namespace
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use CanResetPassword;

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset_password')->with(['token' => $token, 'email' => $request->email]);
    }
}


