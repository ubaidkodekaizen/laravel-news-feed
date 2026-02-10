<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Users\User;
use App\Services\S3Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Str;
use Illuminate\Support\Facades\DB;
use App\Traits\HasUserPhotoData;


class UserController extends Controller
{
    use HasUserPhotoData;
    public function showRegisterForm()
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
            'added_by' => 'web',
        ]);

        Auth::login($user);

        return redirect()->route('user.details.show');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role_id === 1) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role_id !== 4) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.dashboard');
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

            // Check if email is verified
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in. Check your email for the verification link.',
                ]);
            }

            $token = $user->createToken('newsfeed_app')->plainTextToken;

            session(['sanctum_token' => $token]);

            if ($user->role_id === 4) {
                return redirect()->route('news-feed');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showUserDetailsForm()
    {
        $user = Auth::user();

        // Add photo data using the trait
        $this->addPhotoData($user);

        return view('user.user-details', compact('user'));
    }

    public function updateUserDetails(Request $request)
    {
        $normalizeUrl = function ($url) {
            if (!$url) {
                return null;
            }
            $url = trim($url);
            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . $url;
            }
            return $url;
        };

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        $user = User::find(Auth::id());

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '';
        $user->bio = $request->bio ?? '';
        $user->location = $request->location ?? '';
        $user->website = $normalizeUrl($request->website);


        $slug = Str::slug($request->first_name . ' ' . $request->last_name);
        $originalSlug = $slug;
        $counter = 1;

        while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $user->slug = $slug;

        if ($request->hasFile('photo')) {
            $s3Service = app(S3Service::class);
            
            // Delete old photo from S3 if exists
            if ($user->photo) {
                $oldPath = $s3Service->extractPathFromUrl($user->photo);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('photo'), 'profile');
            $user->photo = $uploadResult['url']; // Store full S3 URL
        }
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User details updated successfully!');
    }

    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        // Track profile view
        $this->trackProfileView($user);

        // Use trait to add photo data
        $this->addPhotoData($user);

        return view('user.user-profile', compact('user'));
    }

    /**
     * Track a profile view
     */
    protected function trackProfileView(User $viewedUser)
    {
        // Profile view tracking removed - feature not needed for newsfeed
    }



}
