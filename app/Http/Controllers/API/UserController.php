<?php

namespace App\Http\Controllers\API;

use App\Models\Users\User;
use App\Services\S3Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use App\Traits\FormatsUserData;

class UserController extends Controller
{

    use FormatsUserData;

    /**
     * Return all users with their related objects for external API consumers.
     * Protected via X-API-KEY header.
     */
    public function indexAllWithRelations(Request $request)
    {
        $users = User::with([
            'reactions',
            'postReactions',
            'postComments' => function ($query) {
                $query->with('post:id,content,slug,user_id');
            },
            'posts' => function ($query) {
                $query->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->with([
                        'media',
                        'originalPost.user:id,first_name,last_name,slug,photo',
                        'originalPost.media',
                    ])
                    ->orderBy('created_at', 'desc');
            },
        ])
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'status' => true,
            'count' => $users->count(),
            'data' => UserResource::collection($users),
        ]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $baseSlug = Str::slug($request->first_name . ' ' . $request->last_name);
        $slug = $baseSlug;
        $counter = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'slug' => $slug,
            'role_id' => 4,
            'status' => 'pending',
            // Don't verify email automatically - user needs to verify via email
        ]);

        // Create email verification token
        $verificationToken = Str::random(64);
        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $verificationToken, 'created_at' => now()]
        );

        // Send verification email
        try {
            Mail::to($request->email)->queue(new \App\Mail\EmailVerification($verificationToken));

            \Log::info('Email verification queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            \Log::error('Email verification failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue anyway - user is registered, just email failed
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully. Please check your email to verify your account.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function registerAmcob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $baseSlug = Str::slug($request->first_name . ' ' . $request->last_name);
        $slug = $baseSlug;
        $counter = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'slug' => $slug,
            'role_id' => 4,
            'status' => 'complete',
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }
        $user = Auth::user();

        // Check if email is verified
        if (!$user->email_verified_at) {
            Auth::logout();
            return response()->json([
                'status' => false,
                'message' => 'Please verify your email address before logging in. Check your email for the verification link.',
            ], 403);
        }

        if ($user->role_id !== 4) {
            Auth::logout();
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only users are allowed.',
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        // FCM token registration removed - newsfeed boilerplate doesn't use Firebase

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
            'redirect_to' => 'feed'
        ]);
    }

    /**
     * Logout user and revoke Sanctum token
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                // Firebase and FCM token handling removed - newsfeed boilerplate

                // Revoke the current access token
                $request->user()->currentAccessToken()->delete();

                Log::info('User logged out successfully', [
                    'user_id' => $user->id
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to logout.',
            ], 500);
        }
    }

    public function updatePersonal(Request $request)
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

        $user = User::findOrFail(Auth::id());

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
            $slug = $originalSlug . '-' . $counter++;
        }
        $user->slug = $slug;

        if ($request->hasFile('photo')) {
            $s3Service = app(\App\Services\S3Service::class);

            // Delete old photo from S3 if exists
            if ($user->photo) {
                $oldPath = $s3Service->extractPathFromUrl($user->photo);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }

            $uploadResult = $s3Service->uploadMedia($request->file('photo'), 'profile');
            $user->photo = $uploadResult['url']; // Store full S3 URL in database
        }

        $user->status = 'active';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User personal details updated successfully!',
            'user' => $user,
        ]);
    }

    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully.',
            'user' => $user,
        ]);
    }




    public function deleteUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->queue(new \App\Mail\PasswordReset($token));

            \Log::info('API password reset email queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            \Log::error('API password reset email failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to send email. Please try again later or contact support.',
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Password reset link sent to your email.',
        ]);
    }

    public function getDropdowns()
    {
        $nationalities = [
            "Afghan",
            "Albanian",
            "Algerian",
            "American",
            "Andorran",
            "Angolan",
            "Antiguans",
            "Argentine",
            "Armenian",
            "Australian",
            "Austrian",
            "Azerbaijani",
            "Bahamian",
            "Bahraini",
            "Bangladeshi",
            "Barbadian",
            "Barbudans",
            "Bashkir",
            "Belgian",
            "Belizean",
            "Beninese",
            "Bhutanese",
            "Bolivian",
            "Bosnian",
            "Botswanan",
            "Brazilian",
            "British",
            "Bruneian",
            "Bulgarian",
            "Burkinese",
            "Burmese",
            "Burundian",
            "Cambodian",
            "Cameroonian",
            "Canadian",
            "Cape Verdean",
            "Central African",
            "Chadian",
            "Chilean",
            "Chinese",
            "Colombian",
            "Comorian",
            "Congolese",
            "Costa Rican",
            "Croatian",
            "Cuban",
            "Cypriot",
            "Czech",
            "Danish",
            "Djiboutian",
            "Dominican",
            "East Timorese",
            "Ecuadorean",
            "Egyptian",
            "Emirati",
            "Equatorial Guinean",
            "Eritrean",
            "Estonian",
            "Ethiopian",
            "Fijian",
            "Filipino",
            "Finnish",
            "French",
            "Gabonese",
            "Gambian",
            "Georgian",
            "German",
            "Ghanaian",
            "Greek",
            "Grenadian",
            "Guatemalan",
            "Guinean",
            "Guinea-Bissauan",
            "Guyanese",
            "Haitian",
            "Honduran",
            "Hungarian",
            "Icelander",
            "Indian",
            "Indonesian",
            "Iranian",
            "Iraqi",
            "Irish",
            "Israeli",
            "Italian",
            "Ivorian",
            "Jamaican",
            "Japanese",
            "Jordanian",
            "Kazakh",
            "Kenyan",
            "Kittian and Nevisian",
            "Korean",
            "Kuwaiti",
            "Kyrgyz",
            "Laotian",
            "Latvian",
            "Lebanese",
            "Liberian",
            "Libyan",
            "Liechtensteiner",
            "Lithuanian",
            "Luxembourgian",
            "Macedonian",
            "Malagasy",
            "Malawian",
            "Malaysian",
            "Maldivian",
            "Malian",
            "Malta",
            "Marshallese",
            "Mauritian",
            "Mexican",
            "Micronesian",
            "Moldovan",
            "Monacan",
            "Mongolian",
            "Moroccan",
            "Mozambican",
            "Namibian",
            "Nauruan",
            "Nepalese",
            "New Zealander",
            "Nicaraguan",
            "Nigerian",
            "Nigerien",
            "Norwegian",
            "Omani",
            "Pakistani",
            "Palauan",
            "Panamanian",
            "Papua New Guinean",
            "Paraguayan",
            "Peruvian",
            "Polish",
            "Portuguese",
            "Qatari",
            "Romanian",
            "Russian",
            "Rwandan",
            "Saint Lucian",
            "Salvadoran",
            "Samoan",
            "San Marinese",
            "Sao Tomean",
            "Saudi",
            "Senegalese",
            "Serbian",
            "Seychellois",
            "Sierra Leonean",
            "Singaporean",
            "Slovak",
            "Slovene",
            "Solomon Islander",
            "Somali",
            "South African",
            "South Korean",
            "Spanish",
            "Sri Lankan",
            "Sudanese",
            "Surinamese",
            "Swazi",
            "Swedish",
            "Swiss",
            "Syrian",
            "Taiwanese",
            "Tajikistani",
            "Tanzanian",
            "Thai",
            "Togolese",
            "Tongan",
            "Trinidadian or Tobagonian",
            "Tunisian",
            "Turkish",
            "Turkmen",
            "Tuvaluan",
            "Ugandan",
            "Ukrainian",
            "Uruguayan",
            "Uzbekistani",
            "Vanuatu",
            "Venezuelan",
            "Vietnamese",
            "Yemeni",
            "Zambian",
            "Zimbabwean"
        ];

        $designations = DB::table('designations')->pluck('name');
        $industries = DB::table('industries')->pluck('name');
        // Business types removed - not part of newsfeed boilerplate

        // Company-related dropdowns (employee_sizes, revenue_ranges, company_experiences, 
        // business_locations, business_challenges, business_goals, company_attributes, 
        // technologies, buying_process, categories, ethnicities) removed - not part of newsfeed boilerplate

        return response()->json([
            'status' => true,
            'dropdowns' => [
                'nationalities' => $nationalities,
                'designations' => $designations,
                'industries' => $industries,
                // Company-related dropdowns removed - not part of newsfeed boilerplate
            ],
        ]);
    }

    /**
     * Get users list with specific columns
     * Returns: first_name, last_name, profile_pic, designation, company, phone_number, user_id
     */
    public function getUsers(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $users = User::where('status', 'active')
            ->whereNull('deleted_at')
            ->select([
                'id',
                'first_name',
                'last_name',
                'photo',
                'phone',
                'location',
            ])
            ->paginate($perPage);

        // Transform the data to match the expected format
        $users->getCollection()->transform(function ($user) {
            return [
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => getImageUrl($user->photo),
                'phone_number' => $user->phone,
                'location' => $user->location,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully.',
            'users' => $users,
        ]);
    }


}
