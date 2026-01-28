<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Company;
use App\Models\User;
use App\Models\ProfileView;
use App\Services\S3Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Str;
use Illuminate\Support\Facades\DB;
use App\Traits\HasUserPhotoData;


class UserController extends Controller
{
    use HasUserPhotoData;
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

            $token = $user->createToken('chat_app')->plainTextToken;

            session(['sanctum_token' => $token]);

            // Optimize: Eager load company to avoid N+1 query
            if ($user->role_id === 4) {
                if (!$user->relationLoaded('company')) {
                    $user->load('company');
                }
                if ($user->status === 'complete' && $user->company && $user->company->status === 'complete') {
                    return redirect()->route('our.community');
                } else {
                    return redirect()->route('user.details.show');
                }
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
        $user = Auth::user()->load(['company', 'userIcp']);

        // Add photo data using the trait
        $this->addPhotoData($user);

        // Use relationship instead of separate query
        $company = $user->company;
        $userIcp = $user->userIcp;

        // Get dropdown data
        $designations = \App\Helpers\DropDownHelper::getDesignationsArray();
        $industries = \App\Helpers\DropDownHelper::getIndustriesArray();

        return view('user.user-details', compact('user', 'company', 'userIcp', 'designations', 'industries'));
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
            'linkedin_url' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

        $user = User::find(Auth::id());


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->linkedin_url = $normalizeUrl($request->linkedin_url);
        $user->x_url = $request->x_url ?? '';
        $user->instagram_url = $request->instagram_url ?? '';
        $user->facebook_url = $request->facebook_url ?? '';
        $user->country = $request->country ?? '';
        $user->state = $request->state ?? '';
        $user->city = $request->city ?? '';
        $user->county = $request->county ?? '';
        $user->gender = $request->gender ?? '';
        $user->age_group = $request->age_group ?? '';
        $user->ethnicity = $request->ethnicity ?? $request->other_ethnicity;
        $user->nationality = $request->nationality ?? '';
        $user->marital_status = $request->marital_status ?? $request->other_marital_status;
        $user->tiktok_url = $request->tiktok_url ?? '';
        $user->youtube_url = $request->youtube_url ?? '';
        if ($request->has('are_you') && !empty($request->are_you)) {
            $user->user_position = implode(', ', $request->are_you);
        } else {
            $user->user_position = null;
        }

        $user->languages = $request->languages ?? '';
        // Handle checkbox: if checked, value is 'Yes', if unchecked, it's not in request so set to 'No'
        $user->email_public = $request->has('email_public') && $request->email_public == 'Yes' ? 'Yes' : 'No';
        $user->phone_public = $request->has('phone_public') && $request->phone_public == 'Yes' ? 'Yes' : 'No';


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
        $user->status = 'complete';
        $user->save();

        return redirect()->back()->with('success', 'User details updated successfully!');
    }

    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)
            ->with('company')
            ->firstOrFail();

        // Track profile view
        $this->trackProfileView($user);

        // Use trait to add photo data
        $this->addPhotoData($user);

        // Get profile views count (safe if table doesn't exist)
        try {
            $profileViewsCount = Schema::hasTable('profile_views') ? ($user->profile_views_count ?? 0) : 0;
        } catch (\Exception $e) {
            $profileViewsCount = 0;
        }

        return view('user.user-profile', compact('user', 'profileViewsCount'));
    }

    /**
     * Track a profile view
     */
    protected function trackProfileView(User $viewedUser)
    {
        try {
            // Check if profile_views table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('profile_views')) {
                return;
            }

            // Don't track if user is viewing their own profile
            if (Auth::check() && Auth::id() === $viewedUser->id) {
                return;
            }

            // Get viewer ID (null if not authenticated)
            $viewerId = Auth::check() ? Auth::id() : null;

            // Check if we should track this view (avoid duplicate tracking within same session/day)
            $shouldTrack = true;

            if ($viewerId) {
                // For authenticated users, check if they've viewed this profile today
                $todayView = ProfileView::where('viewed_user_id', $viewedUser->id)
                    ->where('viewer_id', $viewerId)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($todayView) {
                    $shouldTrack = false;
                }
            } else {
                // For anonymous users, check IP address within last hour
                $ipAddress = request()->ip();
                $recentView = ProfileView::where('viewed_user_id', $viewedUser->id)
                    ->where('viewer_id', null)
                    ->where('ip_address', $ipAddress)
                    ->where('created_at', '>=', now()->subHour())
                    ->exists();

                if ($recentView) {
                    $shouldTrack = false;
                }
            }

            if ($shouldTrack) {
                ProfileView::create([
                    'viewed_user_id' => $viewedUser->id,
                    'viewer_id' => $viewerId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail if tracking fails (table doesn't exist, etc.)
            // Log error in development
            if (config('app.debug')) {
                \Log::warning('Profile view tracking failed: ' . $e->getMessage());
            }
        }
    }



    public function searchMosque(Request $request)
    {
        $request->validate([
            'zip' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            // 'state' => 'nullable|string|max:2', // state is 2 alphabets
        ]);

        $mosques = collect();

        // 1. Prioritize zip (exact match)
        if ($request->filled('zip')) {
            $mosques = DB::table('mosques')->where('zip', $request->zip)->get();
        }

        // 2. If not found, try city (case-insensitive)
        if ($mosques->isEmpty() && $request->filled('city')) {
            $mosques = DB::table('mosques')
                ->whereRaw('LOWER(city) = ?', [strtolower($request->city)])
                ->get();
        }

        // 3. If still not found, try state (2-letter exact match)
        // if ($mosques->isEmpty() && $request->filled('state')) {
        //     $mosques = DB::table('mosques')
        //         ->where('state', strtoupper($request->state))
        //         ->get();
        // }

        // 4. If still not found, return "please suggest"
        if ($mosques->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No mosque available, please suggest.',
                'data' => [],
            ]);
        }

        // ✅ Found mosques
        return response()->json([
            'status' => true,
            'message' => 'Mosques found successfully.',
            'data' => $mosques,
        ]);
    }



    public function storeMosque(Request $request)
    {
        $request->validate([
            'mosque_id' => 'nullable|exists:mosques,id',
            'mosque' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $userId = Auth::id();

        // ✅ Case 1: If mosque_id is provided
        if ($request->filled('mosque_id')) {
            \DB::table('user_mosque')->updateOrInsert(
                ['user_id' => $userId],
                ['mosque_id' => $request->mosque_id, 'amount' => $request->amount]
            );

            return response()->json([
                'status' => true,
                'message' => 'Mosque linked successfully.',
            ]);
        }

        if ($request->filled('mosque')) {
            // Create mosque entry
            $mosqueId = \DB::table('mosques')->insertGetId([
                'mosque' => $request->mosque,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add pivot entry
            \DB::table('user_mosque')->updateOrInsert(
                ['user_id' => $userId],
                ['mosque_id' => $mosqueId, 'amount' => $request->amount]
            );

            return response()->json([
                'status' => true,
                'message' => 'New mosque created and linked successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Please provide either mosque_id or mosque name.',
        ], 422);
    }
}
