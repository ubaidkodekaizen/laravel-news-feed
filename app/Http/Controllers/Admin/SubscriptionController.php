<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Subscription;
use App\Models\SubscriptionBilling;
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
            abort(403, 'Unauthorized action.');
        }

        $subscription = Subscription::with(['user', 'plan', 'billings'])
            ->findOrFail($id);

        // Get billing history ordered by event_date descending
        $billingHistory = SubscriptionBilling::where('subscription_id', $subscription->id)
            ->orderBy('event_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total paid from billing history
        $totalPaid = $billingHistory->where('status', 'success')
            ->whereIn('event_type', [SubscriptionBilling::EVENT_CREATED, SubscriptionBilling::EVENT_RENEWED])
            ->sum('amount');

        return view('admin.subscriptions.show', compact('subscription', 'billingHistory', 'totalPaid'));
    }
}
