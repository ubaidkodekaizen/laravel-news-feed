<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;
use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Password;
use Str;





class UserController extends Controller
{
    
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Password reset link sent to your email.');
    }

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
            
            // Check if both user and their associated company's status are "complete"
            if ($user->status === 'complete' && $user->company && $user->company->status === 'complete') {
                return redirect()->route('search');  // Redirect to 'search' route
            } else {
                return redirect()->route('user.details.show');  // Redirect to 'user.details.show' route
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showUserDetailsForm()
    {
        $user = Auth::user();
        return view('user.user-details', compact('user'));
    }

    public function updateUserDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'linkedin_url' => 'nullable|url',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'industry_to_connect' => 'nullable|string|max:255',
            'sub_category_to_connect' => 'nullable|string|max:255',
            'community_interest' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->linkedin_url = $request->linkedin_url;
        $user->x_url = $request->x_url;
        $user->instagram_url = $request->instagram_url;
        $user->facebook_url = $request->facebook_url;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->county = $request->county;
        $user->zip_code = $request->zip_code;
        $user->industry_to_connect = $request->industry_to_connect;
        $user->sub_category_to_connect = $request->sub_category_to_connect;
        $user->community_interest = $request->community_interest;

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

        return redirect()->route('user.company.details');
    }

    public function showUserCompanyForm()
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();

        return view('user.user-company', compact( 'company'));
    }

    public function storeCompanyDetails(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_web_url' => 'nullable|url|max:255',
            'company_linkedin_url' => 'nullable|url|max:255',
            'company_position' => 'nullable|string|max:255',
            'company_about' => 'nullable|string|max:5000',
            'company_revenue' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_country' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:255',
            'company_county' => 'nullable|string|max:255',
            'company_zip_code' => 'nullable|string|max:20',
            'company_no_of_employee' => 'nullable|string|max:255',
            'company_business_type' => 'nullable|string|max:255',
            'company_industry' => 'nullable|string|max:255',
            'company_sub_category' => 'nullable|string|max:255',
            'company_community_service' => 'nullable|string|max:255',
            'company_contribute_to_muslim_community' => 'nullable|string|max:255',
            'company_affiliation_to_muslim_org' => 'nullable|string|max:255',
            'product_service_name' => 'nullable|array',
            'product_service_name.*' => 'nullable|string|max:255',
            'product_service_description' => 'nullable|array',
            'product_service_description.*' => 'nullable|string|max:500',
            'accreditation' => 'nullable|array',
            'accreditation.*' => 'nullable|string|max:255',
            'company_logo' => 'nullable|file|image|max:2048',
        ]);
    
        $user = Auth::user();
    
        $company = Company::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_web_url' => $request->company_web_url,
                'company_linkedin_url' => $request->company_linkedin_url,
                'company_position' => $request->company_position,
                'company_about' => $request->company_about,
                'company_revenue' => $request->company_revenue,
                'company_address' => $request->company_address,
                'company_country' => $request->company_country,
                'company_state' => $request->company_state,
                'company_city' => $request->company_city,
                'company_county' => $request->company_county,
                'company_zip_code' => $request->company_zip_code,
                'company_no_of_employee' => $request->company_no_of_employee,
                'company_business_type' => $request->company_business_type,
                'company_industry' => $request->company_industry,
                'company_sub_category' => $request->company_sub_category,
                'company_community_service' => $request->company_community_service,
                'company_contribute_to_muslim_community' => $request->company_contribute_to_muslim_community,
                'company_affiliation_to_muslim_org' => $request->company_affiliation_to_muslim_org,
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
        
        if ($request->has('accreditation')) {
            foreach ($request->accreditation as $accreditation) {
                if (!empty($accreditation)) {
                    Accreditation::updateOrCreate(
                        [
                            'company_id' => $company->id,
                            'accreditation_name' => $accreditation,
                        ],
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
    
        return redirect()->route('search');
    }
    





}
