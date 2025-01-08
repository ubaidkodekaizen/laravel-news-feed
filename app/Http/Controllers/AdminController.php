<?php

namespace App\Http\Controllers;
use App\Models\Accreditation;
use App\Models\Blog;
use App\Models\BusinessContribution;
use App\Models\BusinessType;
use App\Models\Company;
use App\Models\Event;
use App\Models\MuslimOrganization;
use App\Models\ProductService;
use App\Models\Subscription;
use App\Models\User;
use App\Models\CommunityInterest;
use App\Models\SubCategory;
use App\Models\Industry;
use App\Models\UserEducation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;





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
                return redirect()->route('search');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function showSubscriptions()
    {
        $subscriptions = Subscription::with('user')
            ->orderByDesc('id')
            ->get();

        return view('admin.subscriptions', compact('subscriptions'));
    }


    public function showUsers()
    {
        $users = User::where('role_id', 4)->with('company')->orderByDesc('id')->get();
        return view('admin.users.users', compact('users'));

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

    // public function editCompany($id)
    // {
    //     $user = User::findOrFail($id);
    //     $company = Company::where('user_id', $user->id)->first();

    //     return view('admin.users.edit-company', compact('user', 'company'));
    // }


    public function updateUserDetails(Request $request)
    {


        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
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

        $user = User::find($request->id);


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
        $user->user_position = implode(', ', $request->are_you);
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

        if ($request->has('college_name') && is_array($request->college_name)) {
            foreach ($request->college_name as $index => $college) {
                if (!empty($college)) {
                    UserEducation::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'college_university' => $college,
                            'degree_diploma' => $request->degree[$index] ?? null,
                        ],
                        [
                            'year' => $request->year_graduated[$index] ?? null,
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'User details updated successfully!');
    }

    public function updateCompanyDetails(Request $request)
    {
        //dd($request->all());
        // $request->validate([
        //     'company_name' => 'required|string|max:255',
        //     'company_web_url' => 'nullable|url|max:255',
        //     'company_linkedin_url' => 'nullable|url|max:255',
        //     'company_position' => 'nullable|string',
        //     'company_revenue' => 'nullable|string|max:255',
        //     'company_no_of_employee' => 'nullable|string|max:255',
        //     'company_business_type' => 'nullable|string|max:255',
        //     'company_industry' => 'nullable|string|max:255',
        //     'product_service_name' => 'nullable|array',
        //     'product_service_name.*' => 'nullable|string|max:255',
        //     'product_service_description' => 'nullable|array',
        //     'product_service_description.*' => 'nullable|string|max:500',
        //     'company_logo' => 'nullable|file|image|max:2048',
        // ]);


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


        if ($request->has('product_service_name')) {
            foreach ($request->product_service_name as $index => $serviceName) {
                if (!empty($serviceName)) {
                    ProductService::updateOrCreate(
                        [
                            'company_id' => $company->id,
                            'product_service_name' => $serviceName,
                        ],
                        [
                            'product_service_description' => $request->product_service_description[$index] ?? '',
                            'product_service_area' => $request->product_service_area[$index] ?? '',
                        ]
                    );
                }
            }
        }



        if ($request->hasFile('company_logo')) {
            $photoPath = $request->file('company_logo')->store('profile_photos', 'public');
            $company->company_logo = $photoPath;
            $company->status = "complete";
            $company->save();
        }

        return redirect()->back()->with('success', 'Professional details updated successfully!');
    }





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
            if ($blog->image && Storage::exists('public/' . $blog->image)) {
                Storage::delete('public/' . $blog->image);
            }
            $imagePath = $request->file('image')->store('blogs', 'public');
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

            if ($event->image && Storage::exists('public/' . $event->image)) {
                Storage::delete('public/' . $event->image);
            }

            $imagePath = $request->file('event_image')->store('event_images', 'public');
            $event->image = $imagePath;
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
