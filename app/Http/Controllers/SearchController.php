<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController extends Controller
{



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
            ->select('id', 'name')
            ->get();
    
        return response()->json($subcategories);
    }
    
    public function getSuggestions(Request $request)
    {
        $searchTerm = $request->input('term');

        $product_services = ProductService::where('product_service_name', 'like', '%' . $searchTerm . '%')
                                          ->get(['product_service_name']);

        $companies = Company::where('company_sub_category', 'like', '%' . $searchTerm . '%')
                            ->orWhere('company_industry', 'like', '%' . $searchTerm . '%')
                            ->get(['company_sub_category', 'company_industry']);

        $users = User::where('first_name', 'like', '%' . $searchTerm . '%')   
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                            ->get(['first_name', 'last_name']);               

        $suggestions = [
            'product_services' => $product_services,
            'company_sub_categories' => $companies->pluck('company_sub_category'),
            'company_industries' => $companies->pluck('company_industry'),
            // 'first_name' => $users->pluck('first_name'),
            // 'last_name' => $users->pluck('last_name'),
        ];

        return response()->json($suggestions);
    }
    
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

            // Filter by Position in companies table
            if ($request->filled('company_position')) {
                $positions = is_array($request->company_position) ? $request->company_position : [$request->company_position];
                $query->whereHas('company', function ($query) use ($positions) {
                    $query->whereIn('company_position', $positions);
                });
            }

            // Filter by Industry in companies table
            if ($request->filled('company_industry')) {
                $industries = is_array($request->company_industry) ? $request->company_industry : [$request->company_industry];
                $query->whereHas('company', function ($query) use ($industries) {
                    $query->whereIn('company_industry', $industries);
                });
            }

            // Filter by Sub-Category in companies table
            if ($request->filled('company_sub_category')) {
                $subCategories = is_array($request->company_sub_category) ? $request->company_sub_category : [$request->company_sub_category];
                $query->whereHas('company', function ($query) use ($subCategories) {
                    $query->whereIn('company_sub_category', $subCategories);
                });
            }

            // Filter by Business Type in companies table
            if ($request->filled('company_business_type')) {
                $businessTypes = is_array($request->company_business_type) ? $request->company_business_type : [$request->company_business_type];
                $query->whereHas('company', function ($query) use ($businessTypes) {
                    $query->whereIn('company_business_type', $businessTypes);
                });
            }

            // Filter by Employee Count in companies table
            if ($request->filled('company_no_of_employee')) {
                $employeeCounts = is_array($request->company_no_of_employee) ? $request->company_no_of_employee : [$request->company_no_of_employee];
                $query->whereHas('company', function ($query) use ($employeeCounts) {
                    $query->whereIn('company_no_of_employee', $employeeCounts);
                });
            }

            // Filter by Revenue in companies table
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

        // Fetch filtered results
        $users = $query->orderBy('id', 'desc')->paginate(15);;

        // Return the updated user results
        if ($request->ajax()) {
            return view('partial.search-result', ['users' => $users]);
        }

        // Return the full view if not an AJAX request
        return view('search', ['users' => $users]);
    }




  


   







}
