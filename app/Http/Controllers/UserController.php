<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;
use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Password;
use Str;





class UserController extends Controller
{
    
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.details.show');
        }
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

        Auth::login($user);

        return redirect()->route('user.details.show');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.details.show');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Check if both user and their associated company's status are "complete"
            if ($user->status === 'complete' && $user->company && $user->company->status === 'complete') {
                return redirect()->route('search');  // Redirect to 'search' route
            } else {
                return redirect()->route('user.details.show');  // Redirect to 'user.details.show' route
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showUserDetailsForm()
    {
        $user = Auth::user();
        return view('user.user-details', compact('user'));
    }

    public function updateUserDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'linkedin_url' => 'nullable|url',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'industry_to_connect' => 'nullable|string|max:255',
            'sub_category_to_connect' => 'nullable|string|max:255',
            'community_interest' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->linkedin_url = $request->linkedin_url;
        $user->x_url = $request->x_url;
        $user->instagram_url = $request->instagram_url;
        $user->facebook_url = $request->facebook_url;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->county = $request->county;
        $user->zip_code = $request->zip_code;
        $user->industry_to_connect = $request->industry_to_connect;
        $user->sub_category_to_connect = $request->sub_category_to_connect;
        $user->community_interest = $request->community_interest;

        $slug = Str::slug($request->first_name . ' ' . $request->last_name);
        $originalSlug = $slug;
        $counter = 1;

        while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $user->slug = $slug;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
            
        }
        $user->status = 'complete';
        $user->save();

        return redirect()->route('user.company.details');
    }

    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)
            ->with('company')
            ->firstOrFail();
            

        return view('user-profile', compact('user'));
    }

    
    





}
