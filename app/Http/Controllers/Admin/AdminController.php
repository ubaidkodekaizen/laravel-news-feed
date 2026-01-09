<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference\Accreditation;
use App\Models\Reference\BusinessContribution;
use App\Models\Reference\BusinessType;
use App\Models\Business\Company;
use App\Models\Reference\MuslimOrganization;
use App\Models\Business\ProductService;
use App\Models\Business\Subscription;
use App\Models\User;
use App\Models\Reference\CommunityInterest;
use App\Models\Reference\SubCategory;
use App\Models\Reference\Industry;
use App\Models\Reference\Designation;
use App\Models\UserEducation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\S3Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\PasswordReset;
use App\Mail\WelcomeNewUser;



class AdminController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            }
        }
        return view('auth.admin-login');
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
            if ($user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('our.community');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function getChartData(Request $request)
    {
        // For line/bar charts, use default dates if not provided
        // For pie charts (platforms, account_creation), allow null for all-time data
        $chartType = $request->get('chart_type');
        
        if (in_array($chartType, ['platforms', 'account_creation'])) {
            // Pie charts: allow empty dates for all-time data
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
        } else {
            // Line/bar charts: use default dates
            $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
        }

        switch ($chartType) {
            case 'signups':
                return $this->getSignupsData($startDate, $endDate);
            case 'subscribers':
                return $this->getSubscribersData($startDate, $endDate);
            case 'platforms':
                return $this->getPlatformsData($startDate, $endDate);
            case 'account_creation':
                return $this->getAccountCreationData($startDate, $endDate);
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function getSignupsData($startDate, $endDate)
    {
        $signups = User::where('role_id', 4)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $count = $signups->firstWhere('date', $dateStr);
            $data[] = $count ? (int)$count->count : 0;
            
            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    private function getSubscribersData($startDate, $endDate)
    {
        $paid = Subscription::where('status', 'active')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $cancelled = Subscription::where('status', 'cancelled')
            ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $paidData = [];
        $cancelledData = [];

        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $paidCount = $paid->firstWhere('date', $dateStr);
            $paidData[] = $paidCount ? (int)$paidCount->count : 0;
            
            $cancelledCount = $cancelled->firstWhere('date', $dateStr);
            $cancelledData[] = $cancelledCount ? (int)$cancelledCount->count : 0;
            
            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'paid' => $paidData,
            'cancelled' => $cancelledData
        ]);
    }

    private function getPlatformsData($startDate, $endDate)
    {
        // Get users by their added_by field and map to platforms
        $query = User::where('role_id', 4);
        
        // Only apply date filter if both dates are provided and not empty
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        $users = $query->selectRaw('added_by, COUNT(*) as count')
            ->groupBy('added_by')
            ->get();

        $androidCount = 0;
        $iosCount = 0;
        $webCount = 0;

        foreach ($users as $user) {
            $addedBy = strtolower($user->added_by ?? '');
            // Map: google → Android, apple → iOS, everything else → Web
            if ($addedBy === 'google') {
                $androidCount += (int)$user->count;
            } elseif ($addedBy === 'apple') {
                $iosCount += (int)$user->count;
            } else {
                // Web includes: web, admin, amcob-api, and any other values
                $webCount += (int)$user->count;
            }
        }

        return response()->json([
            'labels' => ['Android', 'iOS', 'Web'],
            'data' => [$androidCount, $iosCount, $webCount]
        ]);
    }

    private function getAccountCreationData($startDate, $endDate)
    {
        $query = User::where('role_id', 4);
        
        // Only apply date filter if both dates are provided and not empty
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        $accounts = $query->selectRaw('added_by, COUNT(*) as count')
            ->groupBy('added_by')
            ->get();

        $webCount = 0;
        $adminCount = 0;
        $appleCount = 0;
        $androidCount = 0;
        $amcobCount = 0;

        foreach ($accounts as $account) {
            $addedBy = strtolower($account->added_by ?? '');
            if ($addedBy === 'web') {
                $webCount += (int)$account->count;
            } elseif ($addedBy === 'admin') {
                $adminCount += (int)$account->count;
            } elseif ($addedBy === 'apple') {
                $appleCount += (int)$account->count;
            } elseif (in_array($addedBy, ['google', 'android'])) {
                $androidCount += (int)$account->count;
            } elseif (in_array($addedBy, ['amcob-api', 'amcob'])) {
                $amcobCount += (int)$account->count;
            }
        }

        // Return in specific order: Web, Apple, Android, Amcob API, Admin
        return response()->json([
            'labels' => ['Web', 'Apple', 'Android', 'Amcob API', 'Admin'],
            'data' => [$webCount, $appleCount, $androidCount, $amcobCount, $adminCount]
        ]);
    }



    public function showUsers(Request $request)
    {
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // $user = User::find($request->user_id);

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



}
