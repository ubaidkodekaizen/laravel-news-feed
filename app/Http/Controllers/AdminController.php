<?php

namespace App\Http\Controllers;
use App\Models\Accreditation;
use App\Models\BusinessContribution;
use App\Models\BusinessType;
use App\Models\Company;
use App\Models\MuslimOrganization;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CommunityInterest;
use App\Models\SubCategory;
use App\Models\Industry;
use Str;


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

    public function showSubscriptions()
    {
        $users = User::with(['company', 'subscriptions'])
            ->whereHas('subscriptions')
            ->orderByDesc('id')
            ->get();
        //dd($users);            

        return view('admin.subscriptions', compact('users'));
    }

    public function showUsers()
    {
        $users = User::where('role_id', 4)->with('company')->orderByDesc('id')->get();
        return view('admin.users', compact('users'));

    }

    public function showUserById($id)
    {
        $user = User::where('id', $id)
            ->with('company')
            ->firstOrFail();

        return view('admin.user-profile', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function editCompany($id)
    {
        $user = User::findOrFail($id);
        $company = Company::where('user_id', $user->id)->first();
    
        return view('admin.edit-company', compact('user', 'company'));
    }


    public function updateUserDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
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

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

        $user = User::find($request->id);


        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->linkedin_url = $request->linkedin_url ?? $request->linkedin_user;
        $user->x_url = $request->x_url;
        $user->instagram_url = $request->instagram_url;
        $user->facebook_url = $request->facebook_url;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->county = $request->county;
        $user->zip_code = $request->zip_code;

        if ($request->industry_to_connect_other) {
            $industry = Industry::updateOrCreate(
                ['name' => $capitalize($request->industry_to_connect_other)],
                ['name' => $capitalize($request->industry_to_connect_other)]
            );
            $user->industry_to_connect = $industry->name;
        } else {
            $user->industry_to_connect = $request->industry_to_connect;
        }

        if ($request->sub_category_to_connect_other) {
            $industryId = Industry::where('name', $request->industry_to_connect_other ?? $request->industry_to_connect)->pluck('id')->first();

            $subCategoryName = ucfirst(strtolower($request->sub_category_to_connect_other));
            $subCategory = SubCategory::updateOrCreate(
                ['name' => $subCategoryName],
                ['name' => $subCategoryName, 'industry_id' => $industryId]
            );
            $user->sub_category_to_connect = $subCategory->name;
        } else {
            $user->sub_category_to_connect = $request->sub_category_to_connect;
        }

        if ($request->community_interest_other) {
            $communityInterest = CommunityInterest::updateOrCreate(
                ['name' => $capitalize($request->community_interest_other)],
                ['name' => $capitalize($request->community_interest_other)]
            );
            $user->community_interest = $communityInterest->name;
        } else {
            $user->community_interest = $request->community_interest;
        }

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

        return redirect()->back();
    }

    public function updateCompanyDetails(Request $request)
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

        $capitalize = function ($value) {
            return $value ? ucwords(strtolower($value)) : null;
        };

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

        if ($request->company_sub_category_other) {
            $industryId = Industry::where('name', $request->company_industry_other ?? $request->company_industry)->pluck('id')->first();

            $subCategoryName = ucfirst(strtolower($request->company_sub_category_other));
            $subCategory = SubCategory::updateOrCreate(
                ['name' => $subCategoryName],
                ['name' => $subCategoryName, 'industry_id' => $industryId]
            );
            $companySubCategory = $subCategory->name;
        } else {
            $companySubCategory = $request->company_sub_category;
        }


        if ($request->company_contribute_to_muslim_community_other) {
            $communityContribution = BusinessContribution::updateOrCreate(
                ['name' => $capitalize($request->company_contribute_to_muslim_community_other)],
                ['name' => $capitalize($request->company_contribute_to_muslim_community_other)]
            );
            $companyContribution = $communityContribution->name;
        } else {
            $companyContribution = $request->company_contribute_to_muslim_community;
        }

        if ($request->company_affiliation_to_muslim_org_other) {
            $organizationAffiliation = MuslimOrganization::updateOrCreate(
                ['name' => $capitalize($request->company_affiliation_to_muslim_org_other)],
                ['name' => $capitalize($request->company_affiliation_to_muslim_org_other)]
            );
            $companyAffiliation = $organizationAffiliation->name;
        } else {
            $companyAffiliation = $request->company_affiliation_to_muslim_org;
        }

        // $user = Auth::user();

        $company = Company::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_web_url' => $request->company_web_url,
                'company_linkedin_url' => $request->company_linkedin_url ?? $request->company_linkedin_user,
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
                'company_community_service' => $request->company_community_service,
                'company_business_type' => $companyBusinessType,
                'company_industry' => $companyIndustry,
                'company_sub_category' => $companySubCategory,
                'company_contribute_to_muslim_community' => $companyContribution,
                'company_affiliation_to_muslim_org' => $companyAffiliation,
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

        return redirect()->back();
    }



    public function showSubscribers()
    {

    }
}
