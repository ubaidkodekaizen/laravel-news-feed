<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Password;
use App\Models\UserEducation;
use Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
            'type' => 'required|string|in:Basic_Explorer_Monthly,Premium_Connect_Monthly,Premium_Connect_Yearly',
            'transactionId' => 'required|string|unique:subscriptions,transaction_id',
            'recieptData' => 'nullable|string',
            'platform' => 'required|in:google,apple',
        ]);

        $user = Auth::user();

        // ✅ Map enum types to plan_id and plan type
        $planMapping = [
            'Basic_Explorer_Monthly' => ['id' => 4, 'type' => 'monthly'],
            'Premium_Connect_Monthly' => ['id' => 1, 'type' => 'monthly'],
            'Premium_Connect_Yearly' => ['id' => 2, 'type' => 'yearly'],
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

        // ✅ Save subscription
        $subscription = new \App\Models\Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->subscription_type = $planData['type']; // Only monthly/yearly
        $subscription->subscription_amount = $plan->plan_amount;
        $subscription->start_date = $request->startDate;
        $subscription->renewal_date = now()->addDays($planData['type'] === 'monthly' ? 30 : 365);
        $subscription->status = 'active';
        $subscription->transaction_id = $request->transactionId;
        $subscription->receipt_data = $request->recieptData;
        $subscription->platform = $request->platform;
        $subscription->save();

        // ✅ Update user
        $user->paid = 'Yes';
        $user->status = 'complete';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Subscription saved successfully.',
            'subscription' => $subscription
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


    public function updateUserDetails(Request $request)
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

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

        $user = User::find(Auth::id());


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
        $user->marital_status = $request->marital_status ?? $request->$request->other_marital_status;
        $user->tiktok_url = $request->tiktok_url ?? '';
        $user->youtube_url = $request->youtube_url ?? '';
        if ($request->has('are_you') && !empty($request->are_you)) {
            $user->user_position = implode(', ', $request->are_you);
        } else {
            $user->user_position = null;
        }

        $user->languages = $request->languages ?? '';
        $user->email_public = $request->email_public ?? 'No';
        $user->phone_public = $request->phone_public ?? 'No';


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
            ->with(['company', 'products', 'services', 'userEducations', 'subscriptions'])
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $typeMapping = [
            'monthly' => 'Premium_Connect_Monthly',
            'yearly' => 'Premium_Connect_Yearly',
        ];

        foreach ($user->subscriptions as $subscription) {
            if ($subscription->plan_id == 4) {
                $subscription->subscription_type = 'Basic_Explorer_Monthly';
            } else {
                $subscription->subscription_type = $typeMapping[$subscription->subscription_type] ?? $subscription->subscription_type;
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




}
