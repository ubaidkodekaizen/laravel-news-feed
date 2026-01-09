<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        $filter = $request->get('filter', 'all');
        
        // If user doesn't have filter permission, force filter to 'all'
        if (!$canFilter && $filter !== 'all') {
            $filter = 'all';
        }
        
        $query = Subscription::with('user');
        
        // Apply filter
        switch ($filter) {
            case 'active':
                // Active subscriptions excluding free
                $query->where('status', 'active')
                      ->where('subscription_type', '!=', 'Free');
                break;
            case 'inactive':
                // Inactive subscriptions excluding free (cancelled, cancel, inactive, etc.)
                $query->whereIn('status', ['cancelled', 'cancel', 'inactive', 'expired', 'suspended'])
                      ->where('subscription_type', '!=', 'Free');
                break;
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
            case 'all':
            default:
                // Show all subscriptions (no filter)
                break;
        }
        
        $subscriptions = $query->orderByDesc('id')->get();

        // Get counts for tabs
        $baseQuery = Subscription::with('user');
        
        // Count all subscriptions
        $allCount = (clone $baseQuery)->count();
        
        // Count active subscriptions (excluding free)
        $activeCount = (clone $baseQuery)->where('status', 'active')
                                          ->where('subscription_type', '!=', 'Free')
                                          ->count();
        
        // Count inactive subscriptions (excluding free)
        $inactiveCount = (clone $baseQuery)->whereIn('status', ['cancelled', 'cancel', 'inactive', 'expired', 'suspended'])
                                            ->where('subscription_type', '!=', 'Free')
                                            ->count();
        
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

        return view('admin.subscriptions', compact('subscriptions', 'counts', 'filter'));
    }
}
