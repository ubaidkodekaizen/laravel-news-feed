<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference\Accreditation;
use App\Models\Content\Blog;
use App\Models\Reference\BusinessContribution;
use App\Models\Reference\BusinessType;
use App\Models\Business\Company;
use App\Models\Content\Event;
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

    public function showSubscriptions()
    {
        $subscriptions = Subscription::with('user')
            ->where(function($query) {
                $query->whereNotIn('platform', ['DB', 'Amcob'])
                      ->orWhereNull('platform');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.subscriptions', compact('subscriptions'));
    }


    public function showUsers()
    {
        $users = User::where('role_id', 4)->with('company')->orderByDesc('id')->get();
        return view('admin.users.users', compact('users'));

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
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }




    // Blog Routes

    public function adminBlogs()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        return view('admin.blogs.blogs', compact('blogs'));
    }


    public function addBlog(Request $request)
    {
        return view('admin.blogs.add-blog');
    }

    public function storeBlog(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);
        if ($id) {
            $blog = Blog::findOrFail($id);
        } else {
            $blog = new Blog();
        }
        if ($request->hasFile('image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($blog->image) {
                $oldPath = $s3Service->extractPathFromUrl($blog->image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('image'), 'blog');
            $imagePath = $uploadResult['url']; // Store full S3 URL
        } else {
            $imagePath = $blog->image;
        }
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->content = $request->content;
        $blog->image = $imagePath;

        $blog->save();

        $message = $id ? 'Blog updated successfully!' : 'Blog created successfully!';
        return redirect()->route('admin.blogs')->with('success', $message);
    }

    public function editBlog($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit-blog', compact('blog'));
    }


    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return redirect()->route('admin.blogs')->with('success', 'Blog deleted successfully!');
    }


    // Event Routes

    public function adminEvents()
    {
        $events = Event::orderBy('id', 'desc')->get();
        return view('admin.events.events', compact('events'));
    }

    public function addEvent(Request $request)
    {
        return view('admin.events.add-event');
    }

    public function storeEvent(Request $request, $id = null)
    {
        $request->validate([
            'event_title' => 'required|string|max:255',
            'event_city' => 'required|string|max:100',
            'event_time' => 'required',
            'event_date' => 'required|date',
            'event_venue' => 'required|string|max:255',
            'event_url' => 'required|url',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $event = $id ? Event::findOrFail($id) : new Event();


        $event->title = $request->input('event_title');
        $event->city = $request->input('event_city');
        $event->time = $request->input('event_time');
        $event->date = $request->input('event_date');
        $event->venue = $request->input('event_venue');
        $event->url = $request->input('event_url');


        if ($request->hasFile('event_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($event->image) {
                $oldPath = $s3Service->extractPathFromUrl($event->image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('event_image'), 'event');
            $event->image = $uploadResult['url']; // Store full S3 URL
        }

        $event->save();

        $message = $id ? 'Event updated successfully!' : 'Event added successfully!';
        return redirect()->route('admin.events')->with('success', $message);
    }


    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit-event', compact('event'));
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        if ($event->image) {
            Storage::delete('public/event_images/' . $event->image);
        }
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }


}
