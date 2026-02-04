<?php

namespace App\Http\Controllers\API;

use App\Models\Business\Company;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\DropDownHelper;

class SearchController extends Controller
{
    // For main search
    public function searchUserCompany(Request $request)
    {
        $query = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete');
            })
            ->with(['company', 'userIcp']);

        // General search query parameter - searches across all fields
        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            
            $query->where(function ($q) use ($searchTerm) {
                // Search in user names
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
                
                // Search in user position/designation
                $q->orWhere('user_position', 'LIKE', "%{$searchTerm}%");
                
                // Search in locations
                $q->orWhere('city', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('state', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('county', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('country', 'LIKE', "%{$searchTerm}%");
                
                // Search in company fields
                $q->orWhereHas('company', function ($companyQuery) use ($searchTerm) {
                    $companyQuery->where('company_industry', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('company_position', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('company_name', 'LIKE', "%{$searchTerm}%");
                });
                
                // Search in products
                $q->orWhereHas('products', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('title', 'LIKE', "%{$searchTerm}%");
                });
                
                // Search in services
                $q->orWhereHas('services', function ($serviceQuery) use ($searchTerm) {
                    $serviceQuery->where('title', 'LIKE', "%{$searchTerm}%");
                });
                
                // Search in business location (userIcp)
                $q->orWhereHas('userIcp', function ($icpQuery) use ($searchTerm) {
                    $icpQuery->where('business_location', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

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

        if ($request->filled('business_location')) {
            $locations = (array) $request->business_location;
            $query->whereHas('userIcp', function ($query) use ($locations) {
                $query->whereIn('business_location', $locations);
            });
        }

        if ($request->filled('company_current_business_challenges')) {
            $challenges = (array) $request->company_current_business_challenges;
            $query->whereHas('userIcp', function ($query) use ($challenges) {
                $query->where(function ($q) use ($challenges) {
                    foreach ($challenges as $challenge) {
                        $q->orWhere('company_current_business_challenges', 'LIKE', '%' . $challenge . '%');
                    }
                });
            });
        }

        if ($request->filled('company_business_goals')) {
            $goals = (array) $request->company_business_goals;
            $query->whereHas('userIcp', function ($query) use ($goals) {
                $query->where(function ($q) use ($goals) {
                    foreach ($goals as $goal) {
                        $q->orWhere('company_business_goals', 'LIKE', '%' . $goal . '%');
                    }
                });
            });
        }

        if ($request->filled('company_attributes')) {
            $attributes = (array) $request->company_attributes;
            $query->whereHas('userIcp', function ($query) use ($attributes) {
                $query->where(function ($q) use ($attributes) {
                    foreach ($attributes as $attribute) {
                        $q->orWhere('company_attributes', 'LIKE', '%' . $attribute . '%');
                    }
                });
            });
        }

        $isDecisionMakerParam = $request->input('is_decision_maker') ?? $request->input('is_decision_maker[]');
        if ($isDecisionMakerParam) {
            $isDecisionMaker = is_array($isDecisionMakerParam) ? $isDecisionMakerParam[0] : $isDecisionMakerParam;
            $query->whereHas('userIcp', function ($query) use ($isDecisionMaker) {
                $decisionMakerValue = ($isDecisionMaker === 'Yes' || $isDecisionMaker === '1' || $isDecisionMaker === 1 || $isDecisionMaker === true || $isDecisionMaker === 'true') ? 1 : 0;
                $query->where('is_decision_maker', $decisionMakerValue);
            });
        }

        // Order results - prioritize non-N/A values but don't exclude them
        $query->orderByRaw("CASE WHEN city IS NULL OR city = 'N/A' OR city = '' THEN 1 ELSE 0 END")
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
        $filters = DropDownHelper::searchFilterMobile();

        return response()->json([
            'status' => true,
            'message' => 'Dropdown filters fetched successfully.',
            'data' => $filters,
        ]);
    }

    public function getSuggestions(Request $request)
    {
        $searchTerm = $request->input('term');

        if (!$searchTerm) {
            return response()->json([
                'status' => false,
                'message' => 'Search term is required.',
            ], 400);
        }

        $products = Product::where('title', 'like', '%' . $searchTerm . '%')
            ->pluck('title');

        $services = Service::where('title', 'like', '%' . $searchTerm . '%')
            ->pluck('title');

        $companies = Company::where('company_industry', 'like', '%' . $searchTerm . '%')
            ->pluck('company_industry');

        $users = User::where('first_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
            ->get(['first_name', 'last_name']);

        return response()->json([
            'status' => true,
            'message' => 'Suggestions fetched successfully.',
            'data' => [
                'product' => $products,
                'service' => $services,
                'company_industry' => $companies,
                'name' => $users // keep key as "name", but value is array of {first_name, last_name}
            ]
        ]);
    }

















}
