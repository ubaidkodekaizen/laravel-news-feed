<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductService;
use App\Models\Service;
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

        $products = Product::where('title', 'like', '%' . $searchTerm . '%')
            ->get(['title']);

        $services = Service::where('title', 'like', '%' . $searchTerm . '%')
            ->get(['title']);

        $companies = Company::where('company_industry', 'like', '%' . $searchTerm . '%')
            ->get(['company_industry']);

        $users = User::where('first_name', 'like', '%' . $searchTerm . '%')
            ->get(['first_name', 'last_name']);

        $suggestions = [
            'products' => $products,
            'services' => $services,
            'company_industries' => $companies->pluck('company_industry'),
            'first_name' => $users,
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
            ->with(['company']);


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
                $query->where(function ($q) use ($industries) {
                    foreach ($industries as $industry) {
                        $q->orWhere('company_industry', 'LIKE', '%' . $industry . '%');
                    }
                });
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

        if ($request->filled('company_experience')) {
            $experiences = is_array($request->company_experience) ? $request->company_experience : [$request->company_experience];
            $query->whereHas('company', function ($query) use ($experiences) {
                $query->whereIn('company_experience', $experiences);
            });
        }


        if ($request->filled('name')) {
            $names = is_array($request->name) ? $request->name : [$request->name];
            $query->whereIn('first_name', $names); // Assuming 'country' is in the users table
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

        if ($request->filled('user_county')) {
            $counties = is_array($request->user_county) ? $request->user_county : [$request->user_county];
            $query->whereIn('county', $counties); // Assuming 'county' is in the users table
        }

        if ($request->filled('user_city')) {
            $cities = is_array($request->user_city) ? $request->user_city : [$request->user_city];
            $query->whereIn('city', $cities); // Assuming 'city' is in the users table
        }

        if ($request->filled('user_position')) {
            $user_positions = is_array($request->user_position) ? $request->user_position : [$request->user_position];
            $query->where(function ($q) use ($user_positions) {
                foreach ($user_positions as $position) {
                    $q->orWhere('user_position', 'LIKE', '%' . $position . '%');
                }
            });
        }

        if ($request->filled('user_gender')) {
            $user_genders = is_array($request->user_gender) ? $request->user_gender : [$request->user_gender];
            $query->whereIn('gender', $user_genders); // Assuming 'gender' is in the users table
        }

        if ($request->filled('user_age_group')) {
            $user_age_groups = is_array($request->user_age_group) ? $request->user_age_group : [$request->user_age_group];
            $query->whereIn('age_group', $user_age_groups); // Assuming 'age_group' is in the users table
        }

        if ($request->filled('marital_status')) {
            $marital_statuses = is_array($request->marital_status) ? $request->marital_status : [$request->marital_status];
            $query->whereIn('marital_status', $marital_statuses); // Assuming 'marital_status' is in the users table
        }

        if ($request->filled('user_ethnicity')) {
            $user_ethnicities = is_array($request->user_ethnicity) ? $request->user_ethnicity : [$request->user_ethnicity];
            $query->whereIn('ethnicity', $user_ethnicities); // Assuming 'ethnicity' is in the users table
        }

        if ($request->filled('user_nationality')) {
            $user_nationalities = is_array($request->user_nationality) ? $request->user_nationality : [$request->user_nationality];
            $query->where(function ($q) use ($user_nationalities) {
                foreach ($user_nationalities as $nationality) {
                    $q->orWhere('nationality', 'LIKE', '%' . $nationality . '%');
                }
            });
        }


        // Filter by Product/Service in product_services table
        if ($request->filled('product_service_name')) {
            $productServices = is_array($request->product_service_name) ? $request->product_service_name : [$request->product_service_name];
            $query->whereHas('company.productServices', function ($query) use ($productServices) {
                $query->whereIn('product_service_name', $productServices);
            });
        }

        if ($request->filled('product')) {
            $products = is_array($request->product) ? $request->product : [$request->product];
            $query->whereHas('products', function ($q) use ($products) {
                $q->whereIn('title', $products); // Assuming 'title' is the column in the products table
            });
        }


        if ($request->filled('service')) {
            $services = is_array($request->service) ? $request->service : [$request->service];
            $query->whereHas('services', function ($q) use ($services) {
                $q->whereIn('title', $services); // Assuming 'title' is the column in the products table
            });
        }

        $query->orderByRaw("CASE WHEN city IS NULL THEN 2 WHEN city = 'N/A' THEN 1 ELSE 0 END")
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
