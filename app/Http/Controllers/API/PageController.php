<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class PageController extends Controller
{
    public function getIndustries()
    {
        $allIndustries = [
            ['icon' => 'fas fa-laptop-code', 'name' => 'Technology'],
            ['icon' => 'fas fa-heartbeat', 'name' => 'Healthcare'],
            ['icon' => 'fa-solid fa-file-invoice-dollar', 'name' => 'Finance'],
            ['icon' => 'fas fa-shopping-cart', 'name' => 'Retail'],
            ['icon' => 'fas fa-tractor', 'name' => 'Agriculture, Forestry, Fishing and Hunting'],
            ['icon' => 'fa-solid fa-oil-well', 'name' => 'Mining, Quarrying, and Oil and Gas Extraction'],
            ['icon' => 'fas fa-bolt', 'name' => 'Utilities'],
            ['icon' => 'fa-solid fa-person-digging', 'name' => 'Construction'],
            ['icon' => 'fas fa-industry', 'name' => 'Manufacturing'],
            ['icon' => 'fas fa-boxes', 'name' => 'Wholesale Trade'],
            ['icon' => 'fas fa-store', 'name' => 'Retail Trade'],
            ['icon' => 'fas fa-truck', 'name' => 'Transportation and Warehousing'],
            ['icon' => 'fas fa-info-circle', 'name' => 'Information'],
            ['icon' => 'fa-solid fa-hand-holding-dollar', 'name' => 'Finance and Insurance'],
            ['icon' => 'fas fa-home', 'name' => 'Real Estate and Rental and Leasing'],
            ['icon' => 'fas fa-flask', 'name' => 'Professional, Scientific, and Technical Services'],
            ['icon' => 'fas fa-building', 'name' => 'Management of Companies and Enterprises'],
            ['icon' => 'fa-solid fa-user-gear', 'name' => 'Administrative and Support and Waste Management and Remediation Services'],
            ['icon' => 'fas fa-graduation-cap', 'name' => 'Educational Services'],
            ['icon' => 'fas fa-hospital', 'name' => 'Health Care and Social Assistance'],
            ['icon' => 'fas fa-theater-masks', 'name' => 'Arts, Entertainment, and Recreation'],
            ['icon' => 'fas fa-utensils', 'name' => 'Accommodation and Food Services'],
            ['icon' => 'fas fa-cogs', 'name' => 'Other Services (except Public Administration)'],
            ['icon' => 'fas fa-landmark', 'name' => 'Public Administration'],
            ['icon' => 'fa-solid fa-ferry', 'name' => 'Navy'],
            ['icon' => 'fas fa-question-circle', 'name' => 'Other'],
        ];

        // Filter industries to only show those that have users with companies in that industry
        $industries = collect($allIndustries)->filter(function ($industry) {
            $industryName = $industry['name'];
            
            // Special handling for "Other" industry
            if (strtolower($industryName) === 'other') {
                return User::where('status', 'complete')
                    ->whereHas('company', function ($query) {
                        $query->where('status', 'complete')
                            ->where(function ($q) {
                                $q->whereNull('company_industry')
                                    ->orWhere('company_industry', '')
                                    ->orWhere('company_industry', 'N/A')
                                    ->orWhere('company_industry', 'Other')
                                    ->orWhere('company_industry', 'other');
                            });
                    })
                    ->exists();
            }
            
            // For all other industries, check if any user has a company with matching industry
            return User::where('status', 'complete')
                ->whereHas('company', function ($query) use ($industryName) {
                    $query->where('status', 'complete')
                        ->where('company_industry', 'LIKE', "%{$industryName}%");
                })
                ->exists();
        })->values()->toArray();

        usort($industries, function ($a, $b) {
            if ($a['name'] === 'Other')
                return 1;
            if ($b['name'] === 'Other')
                return -1;
            return strcmp($a['name'], $b['name']);
        });

        return response()->json([
            'status' => true,
            'industries' => $industries,
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'complete');
            })
            ->orderByDesc('id')
            ->paginate(10); // 👈 change 10 to whatever page size you want

        return response()->json([
            'status' => true,
            'products' => $products,
        ]);
    }

    public function getServices()
    {
        $services = Service::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'complete');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'services' => $services,
        ]);
    }

    public function getIndustryExperts($industry)
    {
        // Industry consolidation mapping - same as ourCommunity
        $industryConsolidation = [
            'Finance' => ['Finance', 'Financial Advisor', 'Financial Services', 'FinTech', 'Sharia Compliant Financial Services', 'Investment', 'Private Equity', 'Residential Mortgage', 'Payment Solution'],
            'Healthcare' => ['Healthcare', 'Medical Practices', 'Medical Billing', 'MedTech', 'Mental Health Therapist', 'Biopharma', 'Pharmaceuticals', 'FemTech'],
            'Technology' => ['Technology', 'Salesforce', 'Salesforce Consulting', 'Telecommunications', '3D Printing'],
            'Marketing' => ['Marketing', 'Marketing Services', 'Digital Marketing', 'Advertising Services'],
            'Construction' => ['Construction', 'Interior design'],
            'Educational Services' => ['Educational Services', 'Coaching'],
            'Legal' => ['Legal', 'Law Practice'],
            'Non-profit' => ['Non Profit', 'Non-profit', 'Non-profit Organizations'],
            'Business Consulting' => ['Business Consulting', 'Business Consulting and Services', 'Outsourcing and Offshoring Consulting'],
            'Staffing' => ['Staffing', 'Head Hunter', 'Resource Augmentation'],
            'Retail' => ['Retail', 'Restaurant', 'Halal Meat'],
            'Real Estate and Rental and Leasing' => ['Real Estate and Rental and Leasing'],
            'Administrative and Support and Waste Management and Remediation Services' => ['Administrative and Support and Waste Management and Remediation Services', 'Cleaning Services'],
            'Professional, Scientific, and Technical Services' => ['Professional, Scientific, and Technical Services', 'Creative Design', 'Writing and Editing', 'Ideation'],
            'Engineering' => ['Engineering', 'mechanical or industrial engineering'],
            'Logistics' => ['Logistics'],
            'Accounting' => ['Accounting'],
            'Printing' => ['Printing'],
            'InsurTech' => ['InsurTech'],
        ];

        // Decode URL-encoded industry name
        $industry = urldecode($industry);

        // Get variations for the industry (if it's a consolidated category)
        $industryVariations = [];
        if (isset($industryConsolidation[$industry])) {
            // If it's a main category, get all its variations
            $industryVariations = $industryConsolidation[$industry];
        } else {
            // If it's not a main category, check if it belongs to one
            foreach ($industryConsolidation as $mainCategory => $variations) {
                foreach ($variations as $variation) {
                    if (strcasecmp($industry, $variation) === 0 || stripos($industry, $variation) !== false || stripos($variation, $industry) !== false) {
                        $industryVariations = $industryConsolidation[$mainCategory];
                        $industry = $mainCategory; // Use the main category name
                        break 2;
                    }
                }
            }
            // If still no match found, use the industry as-is
            if (empty($industryVariations)) {
                $industryVariations = [$industry];
            }
        }

        $users = User::where('status', 'complete')
            ->whereHas('company', function ($query) use ($industry, $industryVariations) {
                $query->where('status', 'complete');
                
                if (strtolower($industry) === 'other') {
                    $query->where(function ($q) {
                        $q->whereNull('company_industry')
                            ->orWhere('company_industry', '')
                            ->orWhere('company_industry', 'N/A')
                            ->orWhere('company_industry', 'Other')
                            ->orWhere('company_industry', 'other');
                    });
                } else {
                    // Search for any of the industry variations
                    $query->where(function ($q) use ($industryVariations) {
                        foreach ($industryVariations as $variation) {
                            $q->orWhere('company_industry', 'LIKE', "%{$variation}%");
                        }
                    });
                }
            })
            ->with('company')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'industry' => $industry,
            'experts' => $users,
        ]);
    }

    public function smartSuggestions()
    {
        $authUser = Auth::user();
        $suggestions = collect();

        try {
            $apiUrl = config('services.muslimlynk.api_url');
            $apiKey = config('services.muslimlynk.api_key_ai');
            $userId = $authUser->id;

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'X-API-Key' => $apiKey,
            ])->get("{$apiUrl}/muslimlynk-ai-suggestions/{$userId}", [
                'top_k' => PHP_INT_MAX, // Unlimited - use maximum integer value
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recommendations = $data['recommendations'] ?? [];

                // Extract user IDs from recommendations (preserving order)
                $userIds = collect($recommendations)->pluck('id')->toArray();

                if (!empty($userIds)) {
                    // Fetch users with their relationships
                    $users = User::with(['company', 'userEducations'])
                        ->whereIn('id', $userIds)
                        ->whereNull('deleted_at')
                        ->get()
                        ->keyBy('id');

                    // Transform API recommendations to match existing response format
                    // Preserve the order from API response
                    $suggestions = collect($recommendations)->map(function ($recommendation) use ($users) {
                        $userId = $recommendation['id'];
                        $user = $users->get($userId);

                        if (!$user) {
                            return null;
                        }

                        // Set score attribute to maintain compatibility with existing response
                        $user->setAttribute('score', (int) ($recommendation['combined_score'] ?? $recommendation['match_score'] ?? 0));
                        // Add match_reasons attribute
                        $user->setAttribute('match_reasons', $recommendation['match_reasons'] ?? []);

                        return $user;
                    })->filter()->values();
                }
            } else {
                Log::warning('Smart suggestions API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Smart suggestions API error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }

        // 👇 Manual pagination for collections (maintaining same response structure)
        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $suggestions->slice($offset, $perPage)->values(),
            $suggestions->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return response()->json([
            'status' => true,
            'users' => $paginated,
        ]);
    }

    public function ourCommunity()
    {
        // Industry consolidation mapping - groups similar industries together
        $industryConsolidation = [
            'Finance' => ['Finance', 'Financial Advisor', 'Financial Services', 'FinTech', 'Sharia Compliant Financial Services', 'Investment', 'Private Equity', 'Residential Mortgage', 'Payment Solution'],
            'Healthcare' => ['Healthcare', 'Medical Practices', 'Medical Billing', 'MedTech', 'Mental Health Therapist', 'Biopharma', 'Pharmaceuticals', 'FemTech'],
            'Technology' => ['Technology', 'Salesforce', 'Salesforce Consulting', 'Telecommunications', '3D Printing'],
            'Marketing' => ['Marketing', 'Marketing Services', 'Digital Marketing', 'Advertising Services'],
            'Construction' => ['Construction', 'Interior design'],
            'Educational Services' => ['Educational Services', 'Coaching'],
            'Legal' => ['Legal', 'Law Practice'],
            'Non-profit' => ['Non Profit', 'Non-profit', 'Non-profit Organizations'],
            'Business Consulting' => ['Business Consulting', 'Business Consulting and Services', 'Outsourcing and Offshoring Consulting'],
            'Staffing' => ['Staffing', 'Head Hunter', 'Resource Augmentation'],
            'Retail' => ['Retail', 'Restaurant', 'Halal Meat'],
            'Real Estate and Rental and Leasing' => ['Real Estate and Rental and Leasing'],
            'Administrative and Support and Waste Management and Remediation Services' => ['Administrative and Support and Waste Management and Remediation Services', 'Cleaning Services'],
            'Professional, Scientific, and Technical Services' => ['Professional, Scientific, and Technical Services', 'Creative Design', 'Writing and Editing', 'Ideation'],
            'Engineering' => ['Engineering', 'mechanical or industrial engineering'],
            'Logistics' => ['Logistics'],
            'Accounting' => ['Accounting'],
            'Printing' => ['Printing'],
            'InsurTech' => ['InsurTech'],
        ];

        // Icon mapping for consolidated industries
        $industryIcons = [
            'Technology' => 'fas fa-laptop-code',
            'Healthcare' => 'fas fa-heartbeat',
            'Finance' => 'fa-solid fa-file-invoice-dollar',
            'Retail' => 'fas fa-shopping-cart',
            'Agriculture, Forestry, Fishing and Hunting' => 'fas fa-tractor',
            'Mining, Quarrying, and Oil and Gas Extraction' => 'fa-solid fa-oil-well',
            'Utilities' => 'fas fa-bolt',
            'Construction' => 'fa-solid fa-person-digging',
            'Manufacturing' => 'fas fa-industry',
            'Wholesale Trade' => 'fas fa-boxes',
            'Retail Trade' => 'fas fa-store',
            'Transportation and Warehousing' => 'fas fa-truck',
            'Information' => 'fas fa-info-circle',
            'Finance and Insurance' => 'fa-solid fa-hand-holding-dollar',
            'Real Estate and Rental and Leasing' => 'fas fa-home',
            'Professional, Scientific, and Technical Services' => 'fas fa-flask',
            'Management of Companies and Enterprises' => 'fas fa-building',
            'Administrative and Support and Waste Management and Remediation Services' => 'fa-solid fa-user-gear',
            'Educational Services' => 'fas fa-graduation-cap',
            'Health Care and Social Assistance' => 'fas fa-hospital',
            'Arts, Entertainment, and Recreation' => 'fas fa-theater-masks',
            'Accommodation and Food Services' => 'fas fa-utensils',
            'Other Services (except Public Administration)' => 'fas fa-cogs',
            'Public Administration' => 'fas fa-landmark',
            'Navy' => 'fa-solid fa-ferry',
            'Marketing' => 'fas fa-bullhorn',
            'Legal' => 'fas fa-gavel',
            'Non-profit' => 'fas fa-hand-holding-heart',
            'Business Consulting' => 'fas fa-briefcase',
            'Staffing' => 'fas fa-users',
            'Engineering' => 'fas fa-cogs',
            'Logistics' => 'fas fa-truck-fast',
            'Accounting' => 'fas fa-calculator',
            'Printing' => 'fas fa-print',
            'InsurTech' => 'fas fa-shield-alt',
            'Other' => 'fas fa-question-circle',
        ];

        // Get actual industries from companies (like DropdownHelper does)
        $actualIndustries = Company::where('status', 'complete')
            ->whereNotNull('company_industry')
            ->where('company_industry', '!=', '')
            ->whereHas('user', function ($query) {
                $query->where('status', 'complete');
            })
            ->pluck('company_industry')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->filter(function ($industry) {
                $industry = trim($industry);
                return !empty($industry) && 
                       strtolower($industry) !== 'n/a' && 
                       strtolower($industry) !== 'other';
            })
            ->values();

        // Check for "Other" industry (null, empty, N/A, or "Other")
        $hasOtherIndustry = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete')
                    ->where(function ($q) {
                        $q->whereNull('company_industry')
                            ->orWhere('company_industry', '')
                            ->orWhere('company_industry', 'N/A')
                            ->orWhere('company_industry', 'Other')
                            ->orWhere('company_industry', 'other');
                    });
            })
            ->exists();

        // Function to consolidate industry name
        $consolidateIndustry = function ($industryName) use ($industryConsolidation) {
            $industryName = trim($industryName);
            
            // Check each consolidation group
            foreach ($industryConsolidation as $mainCategory => $variations) {
                foreach ($variations as $variation) {
                    // Case-insensitive exact match
                    if (strcasecmp($industryName, $variation) === 0) {
                        return $mainCategory;
                    }
                    // Case-insensitive partial match
                    if (stripos($industryName, $variation) !== false || stripos($variation, $industryName) !== false) {
                        return $mainCategory;
                    }
                }
            }
            
            // If no consolidation found, return original name
            return $industryName;
        };

        // Build industries array with consolidation and icons
        $industries = collect($actualIndustries)
            ->map(function ($industryName) use ($consolidateIndustry, $industryIcons) {
                $consolidatedName = $consolidateIndustry($industryName);
                
                // Get icon for consolidated name
                $icon = $industryIcons[$consolidatedName] ?? 'fas fa-industry';
                
                return ['icon' => $icon, 'name' => $consolidatedName];
            })
            ->unique('name')
            ->values();

        // Add "Other" if it exists
        if ($hasOtherIndustry) {
            $industries->push(['icon' => $industryIcons['Other'], 'name' => 'Other']);
        }

        // Sort industries alphabetically (Other at the end)
        $industries = $industries->sort(function ($a, $b) {
            if ($a['name'] === 'Other') return 1;
            if ($b['name'] === 'Other') return -1;
            return strcasecmp($a['name'], $b['name']); // Case-insensitive alphabetical sort
        })->values()->toArray();

        // Get only one product per user (latest product for each user)
        $products = Product::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'complete');
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($product) {
                $user = $product->user;
                $photoPath = $user->photo ?? null;

                // Check if photo exists
                $hasPhoto = $photoPath && Storage::disk('public')->exists($photoPath);

                // Generate initials
                $initials = strtoupper(
                    substr($user->first_name ?? '', 0, 1) .
                    substr($user->last_name ?? '', 0, 1)
                );

                // Add computed properties to the product object
                $product->user_has_photo = $hasPhoto;
                $product->user_initials = $initials;

                return $product;
            });

        // Get only one service per user (latest service for each user) - limit to 3
        $services = Service::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        })
            ->with('user')
            ->orderByDesc('id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values()
            ->take(3)
            ->map(function ($service) {
                $user = $service->user;
                $photoPath = $user->photo ?? null;

                // Check if photo exists
                $hasPhoto = $photoPath && Storage::disk('public')->exists($photoPath);

                // Generate initials
                $initials = strtoupper(
                    substr($user->first_name ?? '', 0, 1) .
                    substr($user->last_name ?? '', 0, 1)
                );

                // Add computed properties to the service object
                $service->user_has_photo = $hasPhoto;
                $service->user_initials = $initials;

                return $service;
            });

        return response()->json([
            'status' => true,
            'industries' => $industries,
            'products' => $products,
            'services' => $services,
        ]);
    }







}
