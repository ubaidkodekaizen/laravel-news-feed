<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Content\Blog;
use App\Models\Business\Company;
use App\Models\Content\Event;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\HasUserPhotoData;

class PageController extends Controller
{

    use HasUserPhotoData;


    public function ourCommunity()
    {
        // Industry consolidation mapping - groups similar industries together
        $industryConsolidation = [
            'Finance' => ['Finance', 'Financial Advisor', 'Financial Services', 'FinTech', 'Sharia Compliant Financial Services', 'Investment', 'Private Equity', 'Residential Mortgage', 'Payment Solution', 'Banking', 'Venture Capital', 'Insurance And Employee Benefit Funds'],
            'Healthcare' => ['Healthcare', 'Medical Practices', 'Medical Billing', 'MedTech', 'Mental Health Therapist', 'Biopharma', 'Pharmaceuticals', 'FemTech', 'Hospital & Health Care', 'Hospitals And Health Care', 'Medical And Diagnostic Laboratories', 'Medical Equipment Manufacturing', 'Mental Health Care', 'Pharmaceutical Manufacturing'],
            'Technology' => ['Technology', 'Salesforce', 'Salesforce Consulting', 'Telecommunications', '3D Printing', 'Computer And Network Security', 'Computer Hardware', 'Data Infrastructure And Analytics', 'It Services And It Consulting', 'It System Installation And Disposal', 'Software', 'Software Development', 'Information And Internet', 'Internet Marketplace', 'Social Networking Platforms', 'Point Of Sale'],
            'Marketing' => ['Marketing', 'Marketing Services', 'Digital Marketing', 'Advertising Services', 'Media Production'],
            'Construction' => ['Construction', 'Interior design'],
            'Educational Services' => ['Educational Services', 'Coaching', 'E-learning Provider', 'Education Management', 'Primary And Secondary Education'],
            'Legal' => ['Legal', 'Law Practice'],
            'Non-profit' => ['Non Profit', 'Non-profit', 'Non-profit Organizations', 'Civic And Social Organizations', 'Philanthropic Fundraising Services', 'Think Tanks', 'Professional Organizations'],
            'Business Consulting' => ['Business Consulting', 'Business Consulting and Services', 'Outsourcing and Offshoring Consulting', 'Strategic Management Services', 'Executive Offices', 'Holding Companies'],
            'Staffing' => ['Staffing', 'Head Hunter', 'Resource Augmentation', 'Human Resources Services'],
            'Retail' => ['Retail', 'Restaurant', 'Halal Meat', 'Food & Beverages', 'Food And Beverage Manufacturing', 'Consumer Services', 'Personal Care', 'Muslim Marketplace'],
            'Real Estate and Rental and Leasing' => ['Real Estate and Rental and Leasing', 'Leasing Non-residential Real Estate'],
            'Administrative and Support and Waste Management and Remediation Services' => ['Administrative and Support and Waste Management and Remediation Services', 'Cleaning Services', 'Facilities Services'],
            'Professional, Scientific, and Technical Services' => ['Professional, Scientific, and Technical Services', 'Creative Design', 'Writing and Editing', 'Ideation', 'Graphic Design', 'Research Services', 'Translation And Localization'],
            'Engineering' => ['Engineering', 'mechanical or industrial engineering'],
            'Logistics' => ['Logistics'],
            'Transportation and Warehousing' => ['Transportation and Warehousing', 'Transportation'],
            'Accounting' => ['Accounting'],
            'Printing' => ['Printing'],
            'InsurTech' => ['InsurTech', 'Insurance And Employee Benefit Funds'],
            'Manufacturing' => ['Manufacturing', 'Automation Machinery Manufacturing', 'Textile Manufacturing', 'Motor Vehicle Manufacturing'],
            'Wholesale Trade' => ['Wholesale Trade', 'Business Supplies & Equipment'],
            'Utilities' => ['Utilities'],
            'Public Administration' => ['Public Administration'],
            'Other Services (except Public Administration)' => ['Other Services (except Public Administration)', 'Wellness And Fitness Services', 'Resources Enterprise'],
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
            $industryNameLower = strtolower($industryName);
            
            // Check each consolidation group
            foreach ($industryConsolidation as $mainCategory => $variations) {
                foreach ($variations as $variation) {
                    $variationLower = strtolower(trim($variation));
                    
                    // Case-insensitive exact match
                    if (strcasecmp($industryName, $variation) === 0) {
                        return $mainCategory;
                    }
                    
                    // Case-insensitive partial match - check if industry name contains variation
                    if (stripos($industryNameLower, $variationLower) !== false) {
                        $pos = stripos($industryNameLower, $variationLower);
                        // Check if it's at the start, end, or has word boundaries
                        if ($pos === 0 || 
                            in_array(substr($industryName, max(0, $pos - 1), 1), [' ', '-', ',', '&']) ||
                            $pos + strlen($variation) >= strlen($industryName) ||
                            in_array(substr($industryName, $pos + strlen($variation), 1), [' ', '-', ',', '&', ''])) {
                            return $mainCategory;
                        }
                    }
                    
                    // Also check if variation contains industry name (for cases where variation is longer)
                    if (stripos($variationLower, $industryNameLower) !== false && strlen($variation) > strlen($industryName)) {
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

        $blogs = Blog::orderByDesc('id')->get();
        $events = Event::orderByDesc('id')->get();

        // Get only one product per user (latest product for each user)
        $products = Product::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'complete');
            })
            ->with('user')
            ->orderByDesc('id')
            ->get()
            ->map(function ($product) {
                $user = $product->user;
                $photoPath = $user->photo ?? null;

                // Check if photo exists - handle both S3 URLs and local storage
                $hasPhoto = false;
                if ($photoPath) {
                    if (str_starts_with($photoPath, 'http')) {
                        // S3 URL - assume it exists
                        $hasPhoto = true;
                    } else {
                        // Local storage - check file existence
                        $hasPhoto = \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);
                    }
                }

                // Generate initials
                $initials = strtoupper(
                    substr($user->first_name, 0, 1) .
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

                // Check if photo exists - handle both S3 URLs and local storage
                $hasPhoto = false;
                if ($photoPath) {
                    if (str_starts_with($photoPath, 'http')) {
                        // S3 URL - assume it exists
                        $hasPhoto = true;
                    } else {
                        // Local storage - check file existence
                        $hasPhoto = \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);
                    }
                }

                // Generate initials
                $initials = strtoupper(
                    substr($user->first_name, 0, 1) .
                        substr($user->last_name ?? '', 0, 1)
                );

                // Add computed properties to the service object
                $service->user_has_photo = $hasPhoto;
                $service->user_initials = $initials;

                return $service;
            });

        return view('pages.our-community', compact('blogs', 'events', 'products', 'services', 'industries'));
    }

    public function products(Request $request)
    {
        $query = Product::with('user')->whereHas('user', function ($query) {
            $query->where('status', 'complete');
        });

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%");
        }

        $products = $query->orderByDesc('id')->get()->map(function ($product) {
            $user = $product->user;
            $photoPath = $user->photo ?? null;

            // Check if photo exists
            $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);

            // Generate initials
            $initials = strtoupper(
                substr($user->first_name, 0, 1) .
                    substr($user->last_name ?? '', 0, 1)
            );

            // Add computed properties to the product object
            $product->user_has_photo = $hasPhoto;
            $product->user_initials = $initials;

            return $product;
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partial.product_cards', compact('products'))->render()
            ]);
        }
        return view('pages.products', compact('products'));
    }


    public function services(Request $request)
    {
        $query = Service::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        });

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%");
        }

        $services = $query->orderByDesc('id')->get()->map(function ($service) {
            $user = $service->user;
            $photoPath = $user->photo ?? null;

            // Check if photo exists
            $hasPhoto = $photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);

            // Generate initials
            $initials = strtoupper(
                substr($user->first_name, 0, 1) .
                    substr($user->last_name ?? '', 0, 1)
            );

            // Add computed properties to the service object
            $service->user_has_photo = $hasPhoto;
            $service->user_initials = $initials;

            return $service;
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partial.service_cards', compact('services'))->render()
            ]);
        }

        return view('pages.services', compact('services'));
    }
    public function industryExperts($industry)
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
            ->get();

        // Add photo data using trait
        $users = $this->addPhotoDataToCollection($users);

        return view('pages.industry', compact('users', 'industry'));
    }

    public function smartSuggestion()
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
                'top_k' => 100,
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

                    // Transform API recommendations to match view expectations
                    // Preserve the order from API response
                    $suggestions = collect($recommendations)->map(function ($recommendation) use ($users) {
                        $userId = $recommendation['id'];
                        $user = $users->get($userId);

                        if (!$user) {
                            return null;
                        }

                        return [
                            'user' => $user,
                            'company' => $user->company,
                            'score' => (int) ($recommendation['combined_score'] ?? $recommendation['match_score'] ?? 0),
                            'match_reasons' => $recommendation['match_reasons'] ?? [],
                        ];
                    })->filter()->values();

                    // Add photo data to all users in the suggestions array
                    $suggestions = $this->addPhotoDataToCollection($suggestions);

                    // Debug: Check if photo data was added
                    if ($suggestions->isNotEmpty()) {
                        $firstUser = $suggestions->first()['user'] ?? null;
                        Log::info('Photo data check', [
                            'has_photo_property' => isset($firstUser->user_has_photo),
                            'has_initials_property' => isset($firstUser->user_initials),
                            'user_attributes' => $firstUser ? array_keys(get_object_vars($firstUser)) : null,
                        ]);
                    }
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

        return view('pages.smart-suggestion', compact('suggestions'));
    }
}
