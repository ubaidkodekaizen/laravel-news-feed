<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\DropDownHelper;

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
    public function searchUserCompany(Request $request)
    {
        $query = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete');
            })
            ->with(['company']);

        // === Apply Filters ===

        if ($request->filled('company_position')) {
            $positions = (array) $request->company_position;
            $query->whereHas('company', function ($query) use ($positions) {
                $query->where(function ($subQuery) use ($positions) {
                    foreach ($positions as $position) {
                        $subQuery->orWhere('company_position', 'like', "%{$position}%");
                    }
                });
            });
        }

        if ($request->filled('company_industry')) {
            $industries = (array) $request->company_industry;
            $query->whereHas('company', function ($query) use ($industries) {
                $query->where(function ($q) use ($industries) {
                    foreach ($industries as $industry) {
                        $q->orWhere('company_industry', 'LIKE', '%' . $industry . '%');
                    }
                });
            });
        }

        if ($request->filled('company_business_type')) {
            $query->whereHas('company', function ($query) use ($request) {
                $query->whereIn('company_business_type', (array) $request->company_business_type);
            });
        }

        if ($request->filled('company_no_of_employee')) {
            $query->whereHas('company', function ($query) use ($request) {
                $query->whereIn('company_no_of_employee', (array) $request->company_no_of_employee);
            });
        }

        if ($request->filled('company_revenue')) {
            $query->whereHas('company', function ($query) use ($request) {
                $query->whereIn('company_revenue', (array) $request->company_revenue);
            });
        }

        if ($request->filled('company_experience')) {
            $query->whereHas('company', function ($query) use ($request) {
                $query->whereIn('company_experience', (array) $request->company_experience);
            });
        }

        if ($request->filled('name')) {
            $query->whereIn('first_name', (array) $request->name);
        }

        if ($request->filled('country')) {
            $query->whereIn('country', (array) $request->country);
        }

        if ($request->filled('state')) {
            $query->whereIn('state', (array) $request->state);
        }

        if ($request->filled('user_county')) {
            $query->whereIn('county', (array) $request->user_county);
        }

        if ($request->filled('user_city')) {
            $query->whereIn('city', (array) $request->user_city);
        }

        if ($request->filled('user_position')) {
            $positions = (array) $request->user_position;
            $query->where(function ($q) use ($positions) {
                foreach ($positions as $position) {
                    $q->orWhere('user_position', 'LIKE', '%' . $position . '%');
                }
            });
        }

        if ($request->filled('user_gender')) {
            $query->whereIn('gender', (array) $request->user_gender);
        }

        if ($request->filled('user_age_group')) {
            $query->whereIn('age_group', (array) $request->user_age_group);
        }

        if ($request->filled('marital_status')) {
            $query->whereIn('marital_status', (array) $request->marital_status);
        }

        if ($request->filled('user_ethnicity')) {
            $query->whereIn('ethnicity', (array) $request->user_ethnicity);
        }

        if ($request->filled('user_nationality')) {
            $nationalities = (array) $request->user_nationality;
            $query->where(function ($q) use ($nationalities) {
                foreach ($nationalities as $nationality) {
                    $q->orWhere('nationality', 'LIKE', '%' . $nationality . '%');
                }
            });
        }

        if ($request->filled('product_service_name')) {
            $query->whereHas('company.productServices', function ($query) use ($request) {
                $query->whereIn('product_service_name', (array) $request->product_service_name);
            });
        }

        if ($request->filled('product')) {
            $query->whereHas('products', function ($q) use ($request) {
                $q->whereIn('title', (array) $request->product);
            });
        }

        if ($request->filled('service')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->whereIn('title', (array) $request->service);
            });
        }

        // === Order & Pagination ===

        $query->orderByRaw("CASE WHEN city IS NULL THEN 2 WHEN city = 'N/A' THEN 1 ELSE 0 END")
            ->orderBy('id', 'desc');

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'total_items' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }


    public function getDropdownFilters()
    {
        $filters = DropDownHelper::searchFilter();

        return response()->json([
            'status' => true,
            'message' => 'Dropdown filters fetched successfully.',
            'data' => $filters,
        ]);
    }
















}
