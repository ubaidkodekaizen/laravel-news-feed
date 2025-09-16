<?php

namespace App\Http\Controllers\API;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Http\Controllers\Controller;


class PageController extends Controller
{
    // public function feed()
    // {
    //     $industries = [
    //         ['icon' => 'fas fa-laptop-code', 'name' => 'Technology'],
    //         ['icon' => 'fas fa-heartbeat', 'name' => 'Healthcare'],
    //         ['icon' => 'fa-solid fa-file-invoice-dollar', 'name' => 'Finance'],
    //         ['icon' => 'fas fa-shopping-cart', 'name' => 'Retail'],
    //         ['icon' => 'fas fa-tractor', 'name' => 'Agriculture, Forestry, Fishing and Hunting'],
    //         ['icon' => 'fa-solid fa-oil-well', 'name' => 'Mining, Quarrying, and Oil and Gas Extraction'],
    //         ['icon' => 'fas fa-bolt', 'name' => 'Utilities'],
    //         ['icon' => 'fa-solid fa-person-digging', 'name' => 'Construction'],
    //         ['icon' => 'fas fa-industry', 'name' => 'Manufacturing'],
    //         ['icon' => 'fas fa-boxes', 'name' => 'Wholesale Trade'],
    //         ['icon' => 'fas fa-store', 'name' => 'Retail Trade'],
    //         ['icon' => 'fas fa-truck', 'name' => 'Transportation and Warehousing'],
    //         ['icon' => 'fas fa-info-circle', 'name' => 'Information'],
    //         ['icon' => 'fa-solid fa-hand-holding-dollar', 'name' => 'Finance and Insurance'],
    //         ['icon' => 'fas fa-home', 'name' => 'Real Estate and Rental and Leasing'],
    //         ['icon' => 'fas fa-flask', 'name' => 'Professional, Scientific, and Technical Services'],
    //         ['icon' => 'fas fa-building', 'name' => 'Management of Companies and Enterprises'],
    //         ['icon' => 'fa-solid fa-user-gear', 'name' => 'Administrative and Support and Waste Management and Remediation Services'],
    //         ['icon' => 'fas fa-graduation-cap', 'name' => 'Educational Services'],
    //         ['icon' => 'fas fa-hospital', 'name' => 'Health Care and Social Assistance'],
    //         ['icon' => 'fas fa-theater-masks', 'name' => 'Arts, Entertainment, and Recreation'],
    //         ['icon' => 'fas fa-utensils', 'name' => 'Accommodation and Food Services'],
    //         ['icon' => 'fas fa-cogs', 'name' => 'Other Services (except Public Administration)'],
    //         ['icon' => 'fas fa-landmark', 'name' => 'Public Administration'],
    //         ['icon' => 'fa-solid fa-ferry', 'name' => 'Navy'],
    //         ['icon' => 'fas fa-question-circle', 'name' => 'Other'],
    //     ];


    //     usort($industries, function ($a, $b) {
    //         if ($a['name'] === 'Other')
    //             return 1;
    //         if ($b['name'] === 'Other')
    //             return -1;
    //         return strcmp($a['name'], $b['name']);
    //     });

    //     $blogs = Blog::orderByDesc('id')->get();
    //     $events = Event::orderByDesc('id')->get();
    //     $products = Product::whereHas('user', function ($query) {
    //         $query->where('status', 'complete');
    //     })
    //         ->orderByDesc('id')
    //         ->get();

    //     $services = Service::whereHas('user', function ($query) {
    //         $query->where('status', 'complete');
    //     })
    //         ->orderByDesc('id')
    //         ->get();

    //     return view('feed', compact('blogs', 'events', 'products', 'services', 'industries'));
    // }


    public function getIndustries()
    {
        $industries = [
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
            ->get();

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
            ->get();

        return response()->json([
            'status' => true,
            'services' => $services,
        ]);
    }



    public function getIndustryExperts($industry)
    {
        $users = User::where('status', 'complete')
            ->whereHas('company', function ($query) use ($industry) {
                if (strtolower($industry) === 'other') {
                    $query->where(function ($q) {
                        $q->whereNull('company_industry')
                            ->orWhere('company_industry', '')
                            ->orWhere('company_industry', 'N/A')
                            ->orWhere('company_industry', 'Other')
                            ->orWhere('company_industry', 'other');
                    });
                } else {
                    $query->where('company_industry', 'LIKE', "%{$industry}%");
                }
            })
            ->with('company')
            ->get();

        return response()->json([
            'status' => true,
            'industry' => $industry,
            'experts' => $users,
        ]);
    }



    public function smartSuggestions()
    {
        $authUser = Auth::user();
        $authCompany = $authUser->company;

        // preload userEducations for efficiency
        $users = User::with(['company', 'userEducations'])
            ->where('id', '!=', $authUser->id)
            ->whereNull('deleted_at')
            ->get();

        $authEducations = $authUser->userEducations;

        $suggestions = $users->map(function ($user) use ($authUser, $authCompany, $authEducations) {
            $score = 0;

            // 📍 Location
            if ($authUser->country && $user->country == $authUser->country)
                $score += 2;
            if ($authUser->state && $user->state == $authUser->state)
                $score += 2;
            if ($authUser->city && $user->city == $authUser->city)
                $score += 3;

            // 🏢 Industry
            if ($authCompany && $user->company && $authCompany->company_industry && $user->company->company_industry) {
                if (stripos($authCompany->company_industry, $user->company->company_industry) !== false) {
                    $score += 5;
                }
            }

            // 🏢 Company type
            if ($authCompany && $user->company && $authCompany->company_business_type == $user->company->company_business_type) {
                $score += 2;
            }

            // 👔 Role
            if ($authCompany && $user->company && $authCompany->company_position && $user->company->company_position) {
                if (stripos($authCompany->company_position, $user->company->company_position) !== false) {
                    $score += 3;
                }
            }

            // 🎓 Education
            foreach ($authEducations as $edu) {
                foreach ($user->userEducations as $uEdu) {
                    if ($edu->college_university && $edu->college_university == $uEdu->college_university) {
                        $score += 3; // same uni
                    }
                    if ($edu->degree_diploma && $edu->degree_diploma == $uEdu->degree_diploma) {
                        $score += 2; // same degree
                    }
                    if ($edu->year && $uEdu->year && abs((int) $edu->year - (int) $uEdu->year) <= 2) {
                        $score += 1; // same grad year range
                    }
                }
            }

            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                'country' => $user->country,
                'state' => $user->state,
                'city' => $user->city,
                'company' => $user->company ? [
                    'name' => $user->company->company_name,
                    'industry' => $user->company->company_industry,
                    'position' => $user->company->company_position,
                    'business_type' => $user->company->company_business_type,
                ] : null,
                'education' => $user->userEducations->map(function ($edu) {
                    return [
                        'college_university' => $edu->college_university,
                        'degree_diploma' => $edu->degree_diploma,
                        'year' => $edu->year,
                    ];
                }),
                'score' => $score,
            ];
        })->sortByDesc('score')->filter(fn($s) => $s['score'] > 0)->values();

        return response()->json([
            'status' => true,
            'message' => 'Smart suggestions fetched successfully.',
            'data' => $suggestions,
        ]);
    }





}
