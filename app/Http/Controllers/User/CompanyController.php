<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Business\Company;
use App\Models\Reference\Designation;
use App\Models\Reference\Industry;
use App\Models\Reference\BusinessType;
use App\Models\UserIcp;
use App\Services\S3Service;
use Auth;
use Illuminate\Http\Request;
use Str;

class CompanyController extends Controller
{


    public function showUserCompanyForm()
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();

        return view('user.user-company', compact('company'));
    }

    public function storeCompanyDetails(Request $request)
    {
        
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


        $companySlug = Str::slug($request->company_name);
        $originalSlug = $companySlug;
        $counter = 1;

        while (Company::where('company_slug', $companySlug)->where('id', '!=', $company->id)->exists()) {
            $companySlug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $company->company_slug = $companySlug ?? '';
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

        // Save ICP data
        UserIcp::updateOrCreate(
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

        return redirect()->back()->with('success', 'Professional details updated successfully!');
    }




}
