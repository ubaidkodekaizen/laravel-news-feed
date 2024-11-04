<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Password;



class UserController extends Controller
{
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        Auth::login($user); // Log the user in upon registration

        return redirect()->route('dashboard'); // Redirect to dashboard or another page
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate(); // Regenerate session to prevent fixation

            return redirect()->intended('dashboard'); // Redirect to intended page
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Password reset link sent to your email.');
    }






    
    public function updateUserProfile(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
          
        ]);

      
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $data = [
            'first_name' => $request->input('first_name', null),
            'last_name' => $request->input('last_name', null),
            'slug' => $request->input('slug', null),
            'email' => $request->input('email', null),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone', null),
            'linkedin_url' => $request->input('linkedin_url', null),
            'x_url' => $request->input('x_url', null),
            'instagram_url' => $request->input('instagram_url', null),
            'facebook_url' => $request->input('facebook_url', null),
            'address' => $request->input('address', null),
            'country' => $request->input('country', null),
            'state' => $request->input('state', null),
            'city' => $request->input('city', null),
            'county' => $request->input('county', null),
            'zip_code' => $request->input('zip_code', null),
            'industry_to_connect' => $request->input('industry_to_connect', null),
            'sub_category_to_connect' => $request->input('sub_category_to_connect', null),
            'community_interest' => $request->input('community_interest', null),
            'status' => 'pending' 
        ];

     
        $user = User::create($data);

       
        return response()->json([
            'success' => true,
            'message' => 'User profile updated successfully!',
            'data' => $user
        ], 201);
    }

}
