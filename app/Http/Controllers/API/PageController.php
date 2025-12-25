<?php

namespace App\Http\Controllers\API;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PageController extends Controller
{
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







}
