<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;
use App\Models\Company;
use App\Models\ProductService;
use Auth;
use Illuminate\Http\Request;
use Str;

class CompanyController extends Controller
{
    

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


    public function showCompanyBySlug($companySlug)
    {
        $company = Company::where('company_slug', $companySlug)
            ->with(['productServices', 'accreditations'])
            ->firstOrFail();

        return view('company-profile', compact('company'));
    }

    
}
