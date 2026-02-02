<?php

namespace App\Http\Controllers\API;

use App\Models\Business\Company;
use App\Models\Chat\Conversation;
use App\Models\Business\Subscription;
use App\Models\User;
use App\Models\ProfileView;
use App\Services\GooglePlayService;
use App\Services\S3Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Models\UserEducation;
use Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Reference\Designation;
use App\Models\Reference\Industry;
use App\Models\Reference\BusinessType;
use App\Models\UserIcp;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Return all users with their related objects for external API consumers.
     * Protected via X-API-KEY header.
     */
    public function indexAllWithRelations(Request $request)
    {
        $users = User::with([
            'company',
            'products',
            'services',
            'subscriptions',
            'userEducations',
            'userIcp',
            'reactions',
            'postReactions',
            'postComments' => function ($query) {
                $query->with('post:id,content,slug,user_id');
            },
            'profileViews' => function ($query) {
                $query->whereNotNull('viewer_id')
                      ->with('viewer:id,first_name,last_name,slug,photo')
                      ->orderBy('created_at', 'desc');
            },
            'viewedProfiles' => function ($query) {
                $query->whereNotNull('viewed_user_id')
                      ->with('viewedUser:id,first_name,last_name,slug,photo')
                      ->orderBy('created_at', 'desc');
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

        // Load conversations manually for each user since the relationship uses two foreign keys
        // Also eager load messages for each conversation
        foreach ($users as $user) {
            $userConversations = Conversation::where(function($query) use ($user) {
                $query->where('user_one_id', $user->id)
                      ->orWhere('user_two_id', $user->id);
            })
            ->with('messages')
            ->get();
            $user->setRelation('conversations', $userConversations);

            // Load user_mosque pivot table data
            $userMosques = DB::table('user_mosque')
                ->where('user_id', $user->id)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'mosque_id' => $item->mosque_id,
                        'amount' => $item->amount,
                    ];
                });
            $user->setRelation('userMosques', $userMosques);

            // Load profile views count (safe if table doesn't exist)
            try {
                $profileViewsCount = Schema::hasTable('profile_views') ? $user->profileViews()->count() : 0;
                $user->setAttribute('profile_views_count', $profileViewsCount);
            } catch (\Exception $e) {
                $user->setAttribute('profile_views_count', 0);
            }
        }

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
            'status' => 'pending',
            'added_by' => 'amcob-api',
            'is_amcob' => 'Yes',
            'paid' => 'Yes',
            'email_verified_at' => now(), // Automatically verify email for AMCOB users
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'subscription_type' => 'Free',
            'subscription_amount' => 0.00,
            'start_date' => now(),
            'renewal_date' => now()->addDays(90), // 90 days free trial for AMCOB users
            'status' => 'active',
            'transaction_id' => null,
            'receipt_data' => null,
            'platform' => 'Amcob',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'user' => $user,
        ]);
    }

    public function handleIapSubscription(Request $request)
    {
        $validated = $request->validate([
            'startDate' => 'required|date',
            'type' => 'required|string|in:Premium_Monthly,Premium_Yearly',
            'transactionId' => 'required|string',
            'recieptData' => 'nullable|string',
            'platform' => 'required|in:google,apple',
        ]);

        $user = Auth::user();

        $planMapping = [
            'Premium_Monthly' => ['id' => 1, 'type' => 'Monthly'],
            'Premium_Yearly' => ['id' => 2, 'type' => 'Yearly'],
        ];

        if (!isset($planMapping[$request->type])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid subscription type.',
            ], 400);
        }

        $planData = $planMapping[$request->type];
        $plan = \App\Models\Business\Plan::find($planData['id']);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found.',
            ], 404);
        }

        \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        $subscription = \App\Models\Subscription::updateOrCreate(
            [
                'transaction_id' => $request->transactionId,
                'user_id' => $user->id,
            ],
            [
                'plan_id' => $plan->id,
                'subscription_type' => $planData['type'],
                'subscription_amount' => $plan->plan_amount,
                'start_date' => $request->startDate,
                'renewal_date' => now()->addDays($planData['type'] === 'Monthly' ? 30 : 365),
                'status' => 'active',
                'receipt_data' => $request->recieptData,
                'platform' => $request->platform,
            ]
        );

        if ($request->platform === 'google') {
            try {
                $this->acknowledgeGoogleSubscription($subscription, $request->type, $request->recieptData);
            } catch (ValidationException $exception) {
                throw $exception;
            } catch (\RuntimeException $exception) {
                return response()->json([
                    'status' => false,
                    'message' => $exception->getMessage(),
                ], 500);
            } catch (\Throwable $exception) {
                Log::error('Failed to acknowledge Google Play subscription: ' . $exception->getMessage(), [
                    'user_id' => $user->id,
                    'plan' => $request->type,
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Unable to verify Google Play subscription at this time.',
                ], 502);
            }
        }

        // ✅ Update user
        $user->paid = 'Yes';
        $user->status = 'complete';
        // Set added_by based on platform (google for Android, apple for iOS)
        $user->added_by = $request->platform === 'google' ? 'google' : 'apple';
        $user->save();

        try {
            Mail::queue(new \App\Mail\AdminNotification($user, $subscription));
            
            \Log::info('Admin notification email queued successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Admin notification email failed to queue', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Subscription saved successfully. Previous subscriptions cancelled.',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Acknowledge a Google Play subscription purchase and flag the stored receipt.
     *
     * @throws \RuntimeException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function acknowledgeGoogleSubscription(Subscription $subscription, string $planType, ?string $rawReceipt): void
    {
        $productId = data_get(config('services.google_play.products'), $planType);

        if (!$productId) {
            throw new \RuntimeException('Google Play product id is not configured.');
        }

        $decodeReceipt = function (?string $payload) use ($subscription) {
            if (empty($payload)) {
                return null;
            }

            $decoded = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Invalid receipt data JSON for Google acknowledgement.', [
                    'subscription_id' => $subscription->id,
                    'error' => json_last_error_msg(),
                ]);

                return null;
            }

            return $decoded;
        };

        $receiptData = $decodeReceipt($subscription->receipt_data) ?? [];
        $newReceiptData = $decodeReceipt($rawReceipt);

        if (is_array($newReceiptData)) {
            $receiptData = array_merge($receiptData, $newReceiptData);
        }

        $purchaseToken = $receiptData['purchaseToken'] ?? $subscription->transaction_id;

        if (!$purchaseToken) {
            throw ValidationException::withMessages([
                'transactionId' => ['Purchase token is required for Google Play acknowledgement.'],
            ]);
        }

        $packageName = $receiptData['packageName'] ?? config('services.google_play.package_name');
        $developerPayload = $receiptData['developerPayload'] ?? null;

        /** @var GooglePlayService $googlePlay */
        $googlePlay = app(GooglePlayService::class);
        $acknowledged = $googlePlay->acknowledgeSubscription($productId, $purchaseToken, $developerPayload, $packageName);

        $receiptData['purchaseToken'] = $purchaseToken;
        $receiptData['acknowledged'] = true;
        $receiptData['acknowledged_at'] = now()->toIso8601String();

        $subscription->receipt_data = json_encode($receiptData);
        $subscription->save();

        // Log acknowledgment status
        if ($acknowledged) {
            Log::info('Google Play subscription acknowledged successfully', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'product_id' => $productId,
            ]);
        } else {
            Log::info('Google Play subscription was already acknowledged', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'product_id' => $productId,
            ]);
        }
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

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
            'redirect_to' => 'feed'
        ]);
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
            'linkedin_url' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        $user = User::findOrFail(Auth::id());

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
        $user->marital_status = $request->marital_status ?? $request->other_marital_status ?? '';
        $user->tiktok_url = $request->tiktok_url ?? '';
        $user->youtube_url = $request->youtube_url ?? '';
        $user->languages = $request->languages ?? '';
        // Handle checkbox: if checked, value is 'Yes', if unchecked, it's not in request so set to 'No'
        $user->email_public = $request->has('email_public') && $request->email_public == 'Yes' ? 'Yes' : 'No';
        $user->phone_public = $request->has('phone_public') && $request->phone_public == 'Yes' ? 'Yes' : 'No';

        if ($request->has('are_you') && !empty($request->are_you)) {
            $user->user_position = implode(', ', $request->are_you);
        } else {
            $user->user_position = null;
        }

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

        $user->status = 'complete';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User personal details updated successfully!',
            'user' => $user,
        ]);
    }

    /**
     * Update Professional Details
     */
    public function updateProfessional(Request $request)
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

        $capitalize = fn($value) => $value ? ucwords(strtolower($value)) : null;

        if ($request->company_position_other) {
            Designation::updateOrCreate(
                ['name' => $capitalize($request->company_position_other)],
                ['name' => $capitalize($request->company_position_other)]
            );
        }

        if ($request->company_business_type_other) {
            $businessType = BusinessType::updateOrCreate(
                ['name' => $capitalize($request->company_business_type_other)],
                ['name' => $capitalize($request->company_business_type_other)]
            );
            $companyBusinessType = $businessType->name;
        } else {
            $companyBusinessType = $request->company_business_type;
        }

        if ($request->company_industry_other) {
            $industry = Industry::updateOrCreate(
                ['name' => $capitalize($request->company_industry_other)],
                ['name' => $capitalize($request->company_industry_other)]
            );
            $companyIndustry = $industry->name;
        } else {
            $companyIndustry = $request->company_industry;
        }

        $user = Auth::user();

        $company = Company::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $request->company_name ?? '',
                'company_web_url' => $request->company_web_url ?? '',
                'company_linkedin_url' => $normalizeUrl($request->company_linkedin_url),
                'company_position' => $request->company_position ?? '',
                'company_revenue' => $request->company_revenue ?? '',
                'company_no_of_employee' => $request->company_no_of_employee ?? '',
                'company_community_service' => $request->company_community_service ?? '',
                'company_business_type' => $companyBusinessType ?? '',
                'company_industry' => $companyIndustry ?? '',
                'company_experience' => $request->company_experience ?? '',
                'company_phone' => $request->company_phone ?? '',
            ]
        );

        $companySlug = Str::slug($request->company_name);
        $originalSlug = $companySlug;
        $counter = 1;
        while (Company::where('company_slug', $companySlug)->where('id', '!=', $company->id)->exists()) {
            $companySlug = $originalSlug . '-' . $counter++;
        }
        $company->company_slug = $companySlug ?? '';
        $company->status = "complete";

        if ($request->hasFile('company_logo')) {
            $s3Service = app(S3Service::class);
            
            // Delete old logo from S3 if exists
            if ($company->company_logo) {
                $oldPath = $s3Service->extractPathFromUrl($company->company_logo);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('company_logo'), 'company');
            $company->company_logo = $uploadResult['url']; // Store full S3 URL
        }

        $company->save();

        $userIcp = UserIcp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_location' => $request->business_location ?? null,
                'is_decision_maker' => $request->is_decision_maker !== null ? ($request->is_decision_maker === 'Yes' || $request->is_decision_maker === '1' || $request->is_decision_maker === 1 || $request->is_decision_maker === true ? 1 : 0) : null,
                'company_current_business_challenges' => $request->company_current_business_challenges ?? null,
                'company_business_goals' => $request->company_business_goals ?? null,
                'company_attributes' => $request->company_attributes ?? null,
                'company_technologies_you_use' => $request->company_technologies_you_use ?? null,
                'company_buying_process' => $request->company_buying_process ?? null,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Professional details updated successfully!',
            'company' => $company,
            'user_icp' => $userIcp,
        ]);
    }



    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)
            ->with([
                'company',
                'products',
                'services',
                'userEducations',
                'userIcp',
                'subscriptions' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Track profile view
        $this->trackProfileView($user);

        $planMapping = [
            'Premium_Monthly' => ['id' => 1, 'type' => 'Monthly'],
            'Premium_Yearly' => ['id' => 2, 'type' => 'Yearly'],
        ];

        foreach ($user->subscriptions as $subscription) {
            foreach ($planMapping as $planName => $planDetails) {
                if (
                    $subscription->plan_id == $planDetails['id'] &&
                    $subscription->subscription_type == $planDetails['type']
                ) {
                    $subscription->subscription_type = $planName;
                    break;
                }
            }
        }

        // Get profile views count (safe if table doesn't exist)
        try {
            $profileViewsCount = Schema::hasTable('profile_views') ? ($user->profile_views_count ?? 0) : 0;
        } catch (\Exception $e) {
            $profileViewsCount = 0;
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully.',
            'user' => $user,
            'profile_views_count' => $profileViewsCount,
        ]);
    }

    /**
     * Track a profile view
     */
    protected function trackProfileView(User $viewedUser)
    {
        try {
            // Check if profile_views table exists
            if (!Schema::hasTable('profile_views')) {
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
                Log::warning('Profile view tracking failed: ' . $e->getMessage());
            }
        }
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
        $business_types = DB::table('bussiness_types')->pluck('name');

        $employee_sizes = [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '501-1000' => '501-1000 employees',
            '1001-5000' => '1001-5000 employees',
            '5001-10,000' => '5001-10,000 employees',
            '10,001+' => '10,001+ employees',
        ];

        $revenue_ranges = [
            '< 1M' => '< $1M',
            '1-5M' => '$1M -$5M',
            '5-25M' => '$5M - $25M',
            '25-100M' => '$25M - $100M',
            '100M +' => '$100M+',
        ];

        $company_experiences = [
            'Under 1',
            '1-5 years',
            '5-10 years',
            '10-20 years',
            '20+ years',
        ];

        $genders = ['Male', 'Female', 'Prefer not to disclose', 'Other'];
        $age_groups = ['20-30', '31-40', '41-50', '51-60', '60+', 'Prefer not to disclose'];
        $marital_statuses = ['Single', 'Married', 'Divorced', 'Prefer not to disclose', 'Other'];

        $business_locations = \App\Helpers\DropDownHelper::getBusinessLocationsArray();
        $business_challenges = \App\Helpers\DropDownHelper::getCurrentBusinessChallengesArray();
        $business_goals = \App\Helpers\DropDownHelper::getBusinessGoalsArray();
        $company_attributes = \App\Helpers\DropDownHelper::getCompanyAttributesArray();
        $technologies = \App\Helpers\DropDownHelper::getTechnologiesArray();
        $buying_process = \App\Helpers\DropDownHelper::getBuyingProcessArray();
        $categories = \App\Helpers\DropDownHelper::getParentCategoriesArray();

        // Ethnicity options - flattened array of all options
        $ethnicities = [
            'North African (e.g., Arab, Berber, Nubian)',
            'Sub-Saharan African (e.g., Hausa, Yoruba, Somali, Zulu)',
            'Afro-Caribbean',
            'African-American',
            'South Asian (e.g., Indian, Pakistani, Bangladeshi, Sri Lankan, Nepali)',
            'East Asian (e.g., Chinese, Japanese, Korean)',
            'Southeast Asian (e.g., Malay, Filipino, Indonesian, Thai, Burmese)',
            'Central Asian (e.g., Kazakh, Uzbek, Turkmen, Tajik, Kyrgyz)',
            'West Asian/Middle Eastern (e.g., Arab, Persian, Kurdish, Turkish, Assyrian)',
            'Eastern European (e.g., Russian, Polish, Ukrainian, Romanian)',
            'Western European (e.g., British, French, German, Dutch)',
            'Southern European (e.g., Italian, Spanish, Greek, Portuguese)',
            'Balkan (e.g., Bosnian, Albanian)',
            'Latino/Hispanic (e.g., Mexican, Brazilian, Colombian, Cuban)',
            'Multiracial/Mixed Heritage',
            'Other',
            'Prefer not to disclose'
        ];

        return response()->json([
            'status' => true,
            'dropdowns' => [
                'nationalities' => $nationalities,
                'designations' => $designations,
                'industries' => $industries,
                'employee_sizes' => $employee_sizes,
                'business_types' => $business_types,
                'revenue_ranges' => $revenue_ranges,
                'company_experiences' => $company_experiences,
                'genders' => $genders,
                'age_groups' => $age_groups,
                'marital_statuses' => $marital_statuses,
                'ethnicity' => $ethnicities,
                'business_locations' => $business_locations,
                'business_challenges' => $business_challenges,
                'business_goals' => $business_goals,
                'company_attributes' => $company_attributes,
                'technologies' => $technologies,
                'buying_process' => $buying_process,
                'categories' => $categories,
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
        
        $users = User::where('status', 'complete')
            ->whereNull('deleted_at')
            ->with('company:id,user_id,company_name,company_position')
            ->select([
                'id',
                'first_name',
                'last_name',
                'photo',
                'phone',
                'city',
                'state',
            ])
            ->paginate($perPage);

        // Transform the data to match the expected format
        $users->getCollection()->transform(function ($user) {
            return [
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_pic' => getImageUrl($user->photo),
                'designation' => $user->company->company_position ?? null,
                'company' => $user->company->company_name ?? null,
                'phone_number' => $user->phone,
                'city' => $user->city,
                'state' => $user->state,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully.',
            'users' => $users,
        ]);
    }




}
