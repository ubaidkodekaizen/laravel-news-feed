<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Company;
use App\Models\Business\Subscription;
use App\Models\User;
use App\Models\Reference\Designation;
use App\Models\Reference\BusinessType;
use App\Models\Reference\Industry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\S3Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordReset;
use App\Mail\WelcomeNewUser;

class AdminUserController extends Controller
{
    public function showUsers(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check if user has view permission
        if (!$isAdmin && (!$user || !$user->hasPermission('users.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all');
        
        $query = User::where('role_id', 4)->with('company');
        
        // Apply filter
        switch ($filter) {
            case 'web':
                $query->where('added_by', 'web');
                break;
            case 'google':
                $query->where('added_by', 'google');
                break;
            case 'apple':
                $query->where('added_by', 'apple');
                break;
            case 'amcob':
                $query->where(function($q) {
                    $q->where('added_by', 'Admin')
                      ->orWhere('added_by', 'amcob-api')
                      ->orWhere('is_amcob', 'Yes');
                });
                break;
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active users only
                break;
        }
        
        $users = $query->orderByDesc('id')->get();
        
        // Get counts for tabs
        $baseQuery = User::where('role_id', 4);
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'web' => (clone $baseQuery)->where('added_by', 'web')->whereNull('deleted_at')->count(),
            'google' => (clone $baseQuery)->where('added_by', 'google')->whereNull('deleted_at')->count(),
            'apple' => (clone $baseQuery)->where('added_by', 'apple')->whereNull('deleted_at')->count(),
            'amcob' => (clone $baseQuery)->where(function($q) {
                $q->where('added_by', 'Admin')
                  ->orWhere('added_by', 'amcob-api')
                  ->orWhere('is_amcob', 'Yes');
            })->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        return view('admin.users.users', compact('users', 'counts', 'filter'));
    }

    public function addUser()
    {
        return view('admin.users.add-user');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create the user with email verified automatically
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Automatically verify email
            'added_by' => 'admin',
            'is_amcob' => $request->amcob_member ?? 'No',
            'duration' => $request->duration ?? '',
        ]);

        // Create subscription for all admin-created users with 90 days free trial
        $isAmcob = ($request->amcob_member ?? 'No') === 'Yes';
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'subscription_type' => 'Free',
            'subscription_amount' => 0.00,
            'start_date' => now(),
            'renewal_date' => now()->addDays(90), // 90 days free trial
            'status' => 'active',
            'transaction_id' => null,
            'receipt_data' => null,
            'platform' => $isAmcob ? 'Amcob' : 'Admin',
        ]);

        // Create password reset token for password setup
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Prepare email data with credentials
        $emailData = [
            'token' => $token,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password, // Include password for email
            'is_amcob' => $request->amcob_member ?? 'No',
            'duration' => $request->duration ?? ''
        ];
        
        try {
            Mail::to($request->email)->queue(new WelcomeNewUser($emailData));
            
            Log::info('Welcome email queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Welcome email failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue anyway - user is created, just email failed
        }
        
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function showUserById($id)
    {
        $user = User::where('id', $id)
            ->with('company')
            ->firstOrFail();

        return view('admin.users.user-profile', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $company = Company::where('user_id', $user->id)->first();
        return view('admin.users.edit-user', compact('user', 'company'));
    }

    public function editCompany($id)
    {
        // Redirect to edit user page which includes company editing
        return $this->editUser($id);
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
            'email' => 'required|email|unique:users,email,' . $request->id,
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

        $user = User::find($request->id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '';
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

        $user->is_amcob = $request->amcob_member ?? 'No';
        $user->duration = $request->duration ?? '';
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

    public function updateCompanyDetails(Request $request)
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

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

        if ($request->company_position_other) {
            $position = Designation::updateOrCreate(
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

        $company = Company::updateOrCreate(
            ['user_id' => $request->user_id],
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
            $companySlug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $company->company_slug = $companySlug;
        $company->status = "complete";
        $company->save();

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
            $company->status = "complete";
            $company->save();
        }

        return redirect()->back()->with('success', 'Professional details updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->back()->with('success', 'User restored successfully!');
    }

    public function adminResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->queue(new PasswordReset($token));
            
            Log::info('Admin password reset email queued successfully', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Admin password reset email failed to queue', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['email' => 'Failed to send email. Please try again later or contact support.']);
        }

        return back()->with('success', 'Password reset link sent to your email.');
    }
}

