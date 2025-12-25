<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PageController extends Controller
{
    public function ourCommunity()
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

        return view('feed', compact('blogs', 'events', 'products', 'services', 'industries'));
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
        return view('products', compact('products'));
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

        return view('services', compact('services'));
    }


    public function industryExperts($industry)
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

        return view('industry', compact('users', 'industry'));
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
                'top_k' => 100, // Unlimited - use maximum integer value
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
      
        return view('user.smart-suggestion', compact('suggestions'));
    }
}
