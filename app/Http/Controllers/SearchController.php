<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController extends Controller
{




    // For Industries in user and company form
    public function getSubcategories($industryName)
    {

        $industry = \DB::table('industries')
            ->where('name', $industryName)
            ->select('id')
            ->first();
        if (!$industry) {
            return response()->json([]);
        }
        $subcategories = \DB::table('sub_categories')
            ->where('industry_id', $industry->id)
            ->where('name', '!=', 'Other')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $otherSubcategory = \DB::table('sub_categories')
            ->where('industry_id', $industry->id)
            ->where('name', 'Other')
            ->select('id', 'name')
            ->first();

        if ($otherSubcategory) {
            $subcategories->push($otherSubcategory);
        }
        return response()->json($subcategories);
    }


    // For suggestions in search bar
    public function getSuggestions(Request $request)
    {
        $searchTerm = $request->input('term');

        $product_services = ProductService::where('product_service_name', 'like', '%' . $searchTerm . '%')
            ->get(['product_service_name']);

        $companies = Company::where('company_industry', 'like', '%' . $searchTerm . '%')
            ->get(['company_industry']);

        $users = User::where('first_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
            ->get(['first_name', 'last_name']);

        $suggestions = [
            'product_services' => $product_services,
            // 'company_sub_categories' => $companies->pluck('company_sub_category'),
            'company_industries' => $companies->pluck('company_industry'),
            // 'first_name' => $users->pluck('first_name'),
            // 'last_name' => $users->pluck('last_name'),
        ];

        return response()->json($suggestions);
    }


    // For main search
    public function SearchUserCompany(Request $request)
    {
        $query = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete');
            })
            ->with([
                'company' => function ($query) {
                    $query->with(['productServices']);
                }
            ]);


        if ($request->filled('company_position')) {
            $positions = is_array($request->company_position) ? $request->company_position : [$request->company_position];
            $query->whereHas('company', function ($query) use ($positions) {
                $query->where(function ($subQuery) use ($positions) {
                    foreach ($positions as $position) {
                        $subQuery->orWhere('company_position', 'like', "%{$position}%");
                    }
                });
            });
        }



        if ($request->filled('company_industry')) {
            $industries = is_array($request->company_industry) ? $request->company_industry : [$request->company_industry];
            $query->whereHas('company', function ($query) use ($industries) {
                $query->whereIn('company_industry', $industries);
            });
        }


        if ($request->filled('company_sub_category')) {
            $subCategories = is_array($request->company_sub_category) ? $request->company_sub_category : [$request->company_sub_category];
            $query->whereHas('company', function ($query) use ($subCategories) {
                $query->whereIn('company_sub_category', $subCategories);
            });
        }


        if ($request->filled('company_business_type')) {
            $businessTypes = is_array($request->company_business_type) ? $request->company_business_type : [$request->company_business_type];
            $query->whereHas('company', function ($query) use ($businessTypes) {
                $query->whereIn('company_business_type', $businessTypes);
            });
        }


        if ($request->filled('company_no_of_employee')) {
            $employeeCounts = is_array($request->company_no_of_employee) ? $request->company_no_of_employee : [$request->company_no_of_employee];
            $query->whereHas('company', function ($query) use ($employeeCounts) {
                $query->whereIn('company_no_of_employee', $employeeCounts);
            });
        }


        if ($request->filled('company_revenue')) {
            $revenues = is_array($request->company_revenue) ? $request->company_revenue : [$request->company_revenue];
            $query->whereHas('company', function ($query) use ($revenues) {
                $query->whereIn('company_revenue', $revenues);
            });
        }

        // Filter by Country in users table
        if ($request->filled('country')) {
            $countries = is_array($request->country) ? $request->country : [$request->country];
            $query->whereIn('country', $countries); // Assuming 'country' is in the users table
        }

        // Filter by State in users table
        if ($request->filled('state')) {
            $states = is_array($request->state) ? $request->state : [$request->state];
            $query->whereIn('state', $states); // Assuming 'state' is in the users table
        }


        // Filter by Product/Service in product_services table
        if ($request->filled('product_service_name')) {
            $productServices = is_array($request->product_service_name) ? $request->product_service_name : [$request->product_service_name];
            $query->whereHas('company.productServices', function ($query) use ($productServices) {
                $query->whereIn('product_service_name', $productServices);
            });
        }

        $query->orderByRaw("CASE WHEN country IS NULL THEN 2 WHEN country = 'N/A' THEN 1 ELSE 0 END")
            ->orderBy('id', 'desc');
        // Fetch filtered results
        $users = $query->paginate(15);

        // Return the updated user results
        if ($request->ajax()) {
            return view('partial.search-result', ['users' => $users]);
        }

        // Return the full view if not an AJAX request
        return view('search', ['users' => $users]);
    }















}
