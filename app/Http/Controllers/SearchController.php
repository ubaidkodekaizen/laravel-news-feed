<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController extends Controller
{


    public function SearchUserCompany(Request $request)
    {
        $query = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete');
            })
            ->with([
                'company' => function ($query) {
                    $query->with(['productServices', 'accreditations']);
                }
            ]);

        // Filter by Position
            if ($request->filled('company_position')) {
                $positions = is_array($request->company_position) ? $request->company_position : [$request->company_position];
                $query->whereHas('company', function ($query) use ($positions) {
                    $query->whereIn('company_position', $positions);
                });
            }

            // Filter by Industry
            if ($request->filled('company_industry')) {
                $industries = is_array($request->company_industry) ? $request->company_industry : [$request->company_industry];
                $query->whereHas('company', function ($query) use ($industries) {
                    $query->whereIn('company_industry', $industries);
                });
            }

            // Filter by Sub-Category
            if ($request->filled('company_sub_category')) {
                $subCategories = is_array($request->company_sub_category) ? $request->company_sub_category : [$request->company_sub_category];
                $query->whereHas('company', function ($query) use ($subCategories) {
                    $query->whereIn('company_sub_category', $subCategories);
                });
            }

            // Filter by Business Type
            if ($request->filled('company_business_type')) {
                $businessTypes = is_array($request->company_business_type) ? $request->company_business_type : [$request->company_business_type];
                $query->whereHas('company', function ($query) use ($businessTypes) {
                    $query->whereIn('company_business_type', $businessTypes);
                });
            }

            // Filter by Employee Count
            if ($request->filled('company_no_of_employee')) {
                $employeeCounts = is_array($request->company_no_of_employee) ? $request->company_no_of_employee : [$request->company_no_of_employee];
                $query->whereHas('company', function ($query) use ($employeeCounts) {
                    $query->whereIn('company_no_of_employee', $employeeCounts);
                });
            }

            // Filter by Revenue
            if ($request->filled('company_revenue')) {
                $revenues = is_array($request->company_revenue) ? $request->company_revenue : [$request->company_revenue];
                $query->whereHas('company', function ($query) use ($revenues) {
                    $query->whereIn('company_revenue', $revenues);
                });
            }

            // Filter by Country
            if ($request->filled('country')) {
                $countries = is_array($request->company_country) ? $request->company_country : [$request->company_country];
                $query->whereIn('country', $countries); // Assuming 'country' is in the users table
            }

            // Filter by State
            if ($request->filled('state')) {
                $states = is_array($request->state) ? $request->state : [$request->state];
                $query->whereIn('state', $states); // Assuming 'state' is in the users table
            }


            // Filter by Product/Service
            if ($request->filled('product_service_name')) {
                $productServices = is_array($request->product_service_name) ? $request->product_service_name : [$request->product_service_name];
                $query->whereHas('company.productServices', function ($query) use ($productServices) {
                    $query->whereIn('product_service_name', $productServices);
                });
            }



        // Fetch filtered results
        $users = $query->orderBy('id', 'desc')->paginate(15);;

        // Return the updated user results
        if ($request->ajax()) {
            return view('partial.search-result', ['users' => $users]);
        }

        // Return the full view if not an AJAX request
        return view('search', ['users' => $users]);
    }




    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)
            ->with('company')
            ->firstOrFail();
            

        return view('user-profile', compact('user'));
    }


    public function showCompanyBySlug($companySlug)
    {
        $company = Company::where('company_slug', $companySlug)
            ->with(['productServices', 'accreditations'])
            ->firstOrFail();

        return view('company-profile', compact('company'));
    }







}
