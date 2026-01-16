<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check if user has view or filter permission
        $canView = $isAdmin || ($user && $user->hasPermission('subscriptions.view'));
        $canFilter = $isAdmin || ($user && $user->hasPermission('subscriptions.filter'));
        
        if (!$canView && !$canFilter) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get separate filter parameters
        $statusFilter = $request->get('status', 'all');
        $platformFilter = $request->get('platform', 'all');
        $typeFilter = $request->get('type', 'all');
        
        // If user doesn't have filter permission, force all filters to 'all'
        if (!$canFilter) {
            $statusFilter = 'all';
            $platformFilter = 'all';
            $typeFilter = 'all';
        }
        
        // Optimize: Only load user columns that are actually used in the view
        // Apply same condition as dashboard subscribers box - exclude Free subscriptions
        $query = Subscription::with('user:id,first_name,last_name,email,phone')
            ->where(function($q) {
                $q->where('subscription_type', '!=', 'Free')
                  ->orWhereNull('subscription_type');
            });
        
        // Apply Status filter (independent)
        if ($statusFilter !== 'all') {
            switch ($statusFilter) {
                case 'active':
                    // Active subscriptions only
                    $query->where('status', 'active');
                    break;
                case 'inactive':
                    // All inactive statuses: cancelled, cancel, inactive, expired, suspended, terminated, etc.
                    $query->whereIn('status', [
                        'cancelled', 
                        'cancel', 
                        'canceled',
                        'inactive', 
                        'expired', 
                        'suspended',
                        'terminated'
                    ]);
                    break;
            }
        }
        
        // Apply Platform filter (independent)
        if ($platformFilter !== 'all') {
            switch ($platformFilter) {
                case 'web':
                    // Web subscriptions: platform is 'Web' or null or not in DB/Amcob/Google/Apple
                    $query->where(function($q) {
                        $q->where('platform', 'Web')
                          ->orWhereNull('platform')
                          ->orWhere(function($subQ) {
                              $subQ->whereNotNull('platform')
                                   ->whereNotIn('platform', ['DB', 'Amcob', 'google', 'apple', 'Google', 'Apple', 'Admin']);
                          });
                    });
                    break;
                case 'google':
                    $query->where(function($q) {
                        $q->where('platform', 'google')
                          ->orWhere('platform', 'Google');
                    });
                    break;
                case 'apple':
                    $query->where(function($q) {
                        $q->where('platform', 'apple')
                          ->orWhere('platform', 'Apple');
                    });
                    break;
                case 'amcob':
                    $query->where(function($q) {
                        $q->where('platform', 'Amcob')
                          ->orWhere('platform', 'DB')
                          ->orWhere('platform', 'Admin');
                    });
                    break;
            }
        }
        
        // Apply Type filter
        if ($typeFilter !== 'all') {
            switch ($typeFilter) {
                case 'monthly':
                    $query->where('subscription_type', 'Monthly');
                    break;
                case 'annual':
                    // Map Annual to Yearly (database uses Yearly)
                    $query->where('subscription_type', 'Yearly');
                    break;
                case 'free':
                    $query->where('subscription_type', 'Free');
                    break;
            }
        }
        
        $subscriptions = $query->orderByDesc('id')->get();

        // Optimize: Get counts efficiently - limit user relationship to only needed columns
        // Apply same condition as dashboard subscribers box - include only Monthly and Yearly subscriptions
        $baseQuery = Subscription::with('user:id,first_name,last_name,email,phone')
            ->whereIn('subscription_type', ['Monthly', 'Yearly']);
        
        // Count all subscriptions
        $allCount = (clone $baseQuery)->count();
        
        // Count active subscriptions (all active, independent of type)
        $activeCount = (clone $baseQuery)->where('status', 'active')->count();
        
        // Count inactive subscriptions (all inactive statuses)
        $inactiveCount = (clone $baseQuery)->whereIn('status', [
            'cancelled', 
            'cancel', 
            'canceled',
            'inactive', 
            'expired', 
            'suspended',
            'terminated'
        ])->count();
        
        // Count web subscriptions
        $webCount = (clone $baseQuery)->where(function($q) {
            $q->where('platform', 'Web')
              ->orWhereNull('platform')
              ->orWhere(function($subQ) {
                  $subQ->whereNotNull('platform')
                       ->whereNotIn('platform', ['DB', 'Amcob', 'google', 'apple', 'Google', 'Apple', 'Admin']);
              });
        })->count();
        
        // Count google subscriptions
        $googleCount = (clone $baseQuery)->where(function($q) {
            $q->where('platform', 'google')
              ->orWhere('platform', 'Google');
        })->count();
        
        // Count apple subscriptions
        $appleCount = (clone $baseQuery)->where(function($q) {
            $q->where('platform', 'apple')
              ->orWhere('platform', 'Apple');
        })->count();
        
        // Count AMCOB subscriptions
        $amcobCount = (clone $baseQuery)->where(function($q) {
            $q->where('platform', 'Amcob')
              ->orWhere('platform', 'DB')
              ->orWhere('platform', 'Admin');
        })->count();
        
        // Count Monthly subscriptions
        $monthlyCount = (clone $baseQuery)->where('subscription_type', 'Monthly')->count();
        
        // Count Annual/Yearly subscriptions
        $annualCount = (clone $baseQuery)->where('subscription_type', 'Yearly')->count();
        
        // Count Free subscriptions
        $freeCount = (clone $baseQuery)->where('subscription_type', 'Free')->count();
        
        $counts = [
            'all' => $allCount,
            'active' => $activeCount,
            'inactive' => $inactiveCount,
            'web' => $webCount,
            'google' => $googleCount,
            'apple' => $appleCount,
            'amcob' => $amcobCount,
            'monthly' => $monthlyCount,
            'annual' => $annualCount,
            'free' => $freeCount,
        ];

        return view('admin.subscriptions', compact('subscriptions', 'counts', 'statusFilter', 'platformFilter', 'typeFilter'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check if user has view permission
        $canView = $isAdmin || ($user && $user->hasPermission('subscriptions.view'));
        
        if (!$canView) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $subscription = Subscription::with(['user', 'plan'])
            ->findOrFail($id);

        // Format dates for display
        $formattedData = [
            'id' => $subscription->id,
            'user' => [
                'id' => $subscription->user->id ?? null,
                'name' => $subscription->user ? trim(($subscription->user->first_name ?? '') . ' ' . ($subscription->user->last_name ?? '')) : 'N/A',
                'email' => $subscription->user->email ?? 'N/A',
                'phone' => $subscription->user->phone ?? 'N/A',
            ],
            'plan' => [
                'id' => $subscription->plan->id ?? null,
                'name' => $subscription->plan->plan_name ?? 'N/A',
            ],
            'subscription_type' => $subscription->subscription_type ?? 'N/A',
            'subscription_amount' => $subscription->subscription_amount ? '$' . number_format($subscription->subscription_amount, 2) : 'N/A',
            'platform' => $subscription->platform ?? 'N/A',
            'status' => ucfirst($subscription->status ?? 'N/A'),
            'transaction_id' => $subscription->transaction_id ?? 'N/A',
            'start_date' => $subscription->start_date ? Carbon::parse($subscription->start_date)->format('F d, Y') : 'N/A',
            'renewal_date' => $subscription->renewal_date ? Carbon::parse($subscription->renewal_date)->format('F d, Y') : 'N/A',
            'expires_at' => $subscription->expires_at ? Carbon::parse($subscription->expires_at)->format('F d, Y') : 'N/A',
            'cancelled_at' => $subscription->cancelled_at ? Carbon::parse($subscription->cancelled_at)->format('F d, Y') : 'N/A',
            'last_renewed_at' => $subscription->last_renewed_at ? Carbon::parse($subscription->last_renewed_at)->format('F d, Y') : 'N/A',
            'renewal_count' => $subscription->renewal_count ?? 0,
            'auto_renewing' => $subscription->auto_renewing ? 'Yes' : 'No',
            'payment_state' => $subscription->payment_state ?? 'N/A',
            'last_checked_at' => $subscription->last_checked_at ? Carbon::parse($subscription->last_checked_at)->format('F d, Y h:i A') : 'N/A',
            'grace_period_ends_at' => $subscription->grace_period_ends_at ? Carbon::parse($subscription->grace_period_ends_at)->format('F d, Y') : 'N/A',
            'renewal_reminder_sent_at' => $subscription->renewal_reminder_sent_at ? Carbon::parse($subscription->renewal_reminder_sent_at)->format('F d, Y h:i A') : 'N/A',
            'created_at' => $subscription->created_at ? $subscription->created_at->format('F d, Y h:i A') : 'N/A',
            'updated_at' => $subscription->updated_at ? $subscription->updated_at->format('F d, Y h:i A') : 'N/A',
            'receipt_data' => $subscription->receipt_data ? (is_string($subscription->receipt_data) ? json_decode($subscription->receipt_data, true) : $subscription->receipt_data) : null,
        ];

        return response()->json($formattedData);
    }
}
