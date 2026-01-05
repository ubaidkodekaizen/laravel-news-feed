<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

            // Check if email is verified
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in. Check your email for the verification link.',
                ]);
            }

            $token = $user->createToken('chat_app')->plainTextToken;

            session(['sanctum_token' => $token]);


            if ($user->role_id === 4) {
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
        $user = Auth::user();

        // Add photo data using the trait
        $this->addPhotoData($user);

        $company = Company::where('user_id', $user->id)->first();
        $userIcp = \App\Models\UserIcp::where('user_id', $user->id)->first();

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
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

        // Use trait to add photo data
        $this->addPhotoData($user);

        return view('user.user-profile', compact('user'));
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
