<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserEducation;
use Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Designation;
use App\Models\Industry;
use App\Models\BusinessType;

class UserController extends Controller
{


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
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function handleIapSubscription(Request $request)
    {
        $validated = $request->validate([
            'startDate' => 'required|date',
            'type' => 'required|string|in:Basic_Monthly,Premium_Monthly,Basic_Yearly,Premium_Yearly',
            'transactionId' => 'required|string',
            'recieptData' => 'nullable|string',
            'platform' => 'required|in:google,apple',
        ]);

        $user = Auth::user();

        // ✅ Map enum types to plan_id and plan type
        $planMapping = [
            // 'Basic_Monthly' => ['id' => 4, 'type' => 'monthly'],
            'Premium_Monthly' => ['id' => 1, 'type' => 'monthly'],
            // 'Basic_Yearly' => ['id' => 5, 'type' => 'yearly'],
            'Premium_Yearly' => ['id' => 2, 'type' => 'yearly'],
        ];

        if (!isset($planMapping[$request->type])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid subscription type.',
            ], 400);
        }

        $planData = $planMapping[$request->type];
        $plan = \App\Models\Plan::find($planData['id']);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found.',
            ], 404);
        }

        // ✅ Cancel all previous active subscriptions for this user
        \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // ✅ Create or update the new subscription
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
                'renewal_date' => now()->addDays($planData['type'] === 'monthly' ? 30 : 365),
                'status' => 'active',
                'receipt_data' => $request->recieptData,
                'platform' => $request->platform,
            ]
        );

        // ✅ Update user
        $user->paid = 'Yes';
        $user->status = 'complete';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Subscription saved successfully. Previous subscriptions cancelled.',
            'subscription' => $subscription,
        ]);
    }


    // public function handleIapSubscription(Request $request)
    // {
    //     $validated = $request->validate([
    //         'startDate' => 'required|date',
    //         'type' => 'required|string|in:Basic_Monthly,Premium_Monthly,Basic_Yearly,Premium_Yearly',
    //         'transactionId' => 'required|string|unique:subscriptions,transaction_id',
    //         'recieptData' => 'nullable|string',
    //         'platform' => 'required|in:google,apple',
    //     ]);

    //     $user = Auth::user();

    //     // ✅ Map enum types to plan_id and plan type
    //     $planMapping = [
    //         'Basic_Monthly' => ['id' => 4, 'type' => 'monthly'],
    //         'Premium_Monthly' => ['id' => 1, 'type' => 'monthly'],
    //         'Basic_Yearly' => ['id' => 5, 'type' => 'yearly'],
    //         'Premium_Yearly' => ['id' => 2, 'type' => 'yearly'],
    //     ]; 

    //     if (!isset($planMapping[$request->type])) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid subscription type.',
    //         ], 400);
    //     }

    //     $planData = $planMapping[$request->type];
    //     $plan = \App\Models\Plan::find($planData['id']);

    //     if (!$plan) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Plan not found.',
    //         ], 404);
    //     }

    //     // ✅ Save subscription
    //     $subscription = new \App\Models\Subscription();
    //     $subscription->user_id = $user->id;
    //     $subscription->plan_id = $plan->id;
    //     $subscription->subscription_type = $planData['type']; // Only monthly/yearly
    //     $subscription->subscription_amount = $plan->plan_amount;
    //     $subscription->start_date = $request->startDate;
    //     $subscription->renewal_date = now()->addDays($planData['type'] === 'monthly' ? 30 : 365);
    //     $subscription->status = 'active';
    //     $subscription->transaction_id = $request->transactionId;
    //     $subscription->receipt_data = $request->recieptData;
    //     $subscription->platform = $request->platform;
    //     $subscription->save();

    //     // ✅ Update user
    //     $user->paid = 'Yes';
    //     $user->status = 'complete';
    //     $user->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Subscription saved successfully.',
    //         'subscription' => $subscription
    //     ]);
    // }


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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'linkedin_url' => 'nullable|url',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail(Auth::id());

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->linkedin_url = $request->linkedin_url ?? $request->linkedin_user;
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
        $user->email_public = $request->email_public ?? 'No';
        $user->phone_public = $request->phone_public ?? 'No';

        // user positions (checkboxes)
        if ($request->has('are_you') && !empty($request->are_you)) {
            $user->user_position = implode(', ', $request->are_you);
        } else {
            $user->user_position = null;
        }

        // Unique slug
        $slug = Str::slug($request->first_name . ' ' . $request->last_name);
        $originalSlug = $slug;
        $counter = 1;
        while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $user->slug = $slug;

        // profile photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
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
                'company_linkedin_url' => $request->company_linkedin_url ?? $request->company_linkedin_user,
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

        // Unique company slug
        $companySlug = Str::slug($request->company_name);
        $originalSlug = $companySlug;
        $counter = 1;
        while (Company::where('company_slug', $companySlug)->where('id', '!=', $company->id)->exists()) {
            $companySlug = $originalSlug . '-' . $counter++;
        }
        $company->company_slug = $companySlug ?? '';
        $company->status = "complete";

        // company logo
        if ($request->hasFile('company_logo')) {
            $photoPath = $request->file('company_logo')->store('profile_photos', 'public');
            $company->company_logo = $photoPath;
        }

        $company->save();

        return response()->json([
            'status' => true,
            'message' => 'Professional details updated successfully!',
            'company' => $company,
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

        $planMapping = [
            // 'Basic_Monthly' => ['id' => 4, 'type' => 'monthly'],
            'Premium_Monthly' => ['id' => 1, 'type' => 'monthly'],
            // 'Basic_Yearly' => ['id' => 5, 'type' => 'yearly'],
            'Premium_Yearly' => ['id' => 2, 'type' => 'yearly'],
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

        Mail::send('emails.password-reset', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

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

        return response()->json([
            'status' => true,
            'dropdowns' => [
                'nationalities' => $nationalities,
                'designations' => $designations,
                'industries' => $industries,
                'employee_sizes' => $employee_sizes,
                'business_types' => $business_types,
                'revenue_ranges' => $revenue_ranges,
            ],
        ]);
    }




}
