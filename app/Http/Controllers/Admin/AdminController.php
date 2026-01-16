<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\Business\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->refresh(); // Refresh to get latest role_id from database
            // Allow Admin (1), Manager (2), and Editor (3) to access admin dashboard
            if (in_array((int) $user->role_id, [1, 2, 3])) {
                return redirect()->route('admin.dashboard');
            } else {
                // If user has different role, logout and show login form
                Auth::logout();
                return view('auth.admin-login');
            }
        }
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Refresh user data from database to ensure role_id is current
            $user = Auth::user();
            $user->refresh(); // Refresh to get latest data from database
            
            // Allow Admin (1), Manager (2), and Editor (3) to access admin dashboard
            if (in_array((int) $user->role_id, [1, 2, 3])) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Access denied. Only Admin, Manager, and Editor roles can access the admin panel.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function getChartData(Request $request)
    {
        // For line/bar charts, use default dates if not provided
        // For pie charts (platforms, account_creation), allow null for all-time data
        $chartType = $request->get('chart_type');
        
        if (in_array($chartType, ['platforms', 'account_creation'])) {
            // Pie charts: allow empty dates for all-time data
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
        } else {
            // Line/bar charts: use default dates (7 days for signups and subscribers)
            if (in_array($chartType, ['signups', 'subscribers'])) {
                $startDate = $request->get('start_date', now()->subDays(7)->format('Y-m-d'));
            } else {
                $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
            }
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
        }

        switch ($chartType) {
            case 'signups':
                return $this->getSignupsData($startDate, $endDate);
            case 'subscribers':
                return $this->getSubscribersData($startDate, $endDate);
            case 'platforms':
                return $this->getPlatformsData($startDate, $endDate);
            case 'account_creation':
                return $this->getAccountCreationData($startDate, $endDate);
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function getSignupsData($startDate, $endDate)
    {
        $signups = User::where('role_id', 4)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $count = $signups->firstWhere('date', $dateStr);
            $data[] = $count ? (int)$count->count : 0;
            
            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    private function getSubscribersData($startDate, $endDate)
    {
        // Active subscriptions created on that day - only Monthly and Yearly
        $active = Subscription::where('status', 'active')
            ->whereIn('subscription_type', ['Monthly', 'Yearly'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Renewed subscriptions (where last_renewed_at is within the date range) - only Monthly and Yearly
        $renewed = Subscription::where('status', 'active')
            ->whereIn('subscription_type', ['Monthly', 'Yearly'])
            ->whereNotNull('last_renewed_at')
            ->whereBetween('last_renewed_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(last_renewed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Cancelled subscriptions - only Monthly and Yearly
        $cancelled = Subscription::where('status', 'cancelled')
            ->whereIn('subscription_type', ['Monthly', 'Yearly'])
            ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $activeData = [];
        $renewedData = [];
        $cancelledData = [];

        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $activeCount = $active->firstWhere('date', $dateStr);
            $activeData[] = $activeCount ? (int)$activeCount->count : 0;
            
            $renewedCount = $renewed->firstWhere('date', $dateStr);
            $renewedData[] = $renewedCount ? (int)$renewedCount->count : 0;
            
            $cancelledCount = $cancelled->firstWhere('date', $dateStr);
            $cancelledData[] = $cancelledCount ? (int)$cancelledCount->count : 0;
            
            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'active' => $activeData,
            'renewed' => $renewedData,
            'cancelled' => $cancelledData
        ]);
    }

    private function getPlatformsData($startDate, $endDate)
    {
        // Get users by their added_by field and map to platforms
        $query = User::where('role_id', 4);
        
        // Only apply date filter if both dates are provided and not empty
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        $users = $query->selectRaw('added_by, COUNT(*) as count')
            ->groupBy('added_by')
            ->get();

        $androidCount = 0;
        $iosCount = 0;
        $webCount = 0;

        foreach ($users as $user) {
            $addedBy = strtolower($user->added_by ?? '');
            // Map: google → Android, apple → iOS, everything else → Web
            if ($addedBy === 'google') {
                $androidCount += (int)$user->count;
            } elseif ($addedBy === 'apple') {
                $iosCount += (int)$user->count;
            } else {
                // Web includes: web, admin, amcob-api, and any other values
                $webCount += (int)$user->count;
            }
        }

        return response()->json([
            'labels' => ['Android', 'iOS', 'Web'],
            'data' => [$androidCount, $iosCount, $webCount]
        ]);
    }

    private function getAccountCreationData($startDate, $endDate)
    {
        $query = User::where('role_id', 4);
        
        // Only apply date filter if both dates are provided and not empty
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        $accounts = $query->selectRaw('added_by, COUNT(*) as count')
            ->groupBy('added_by')
            ->get();

        $webCount = 0;
        $adminCount = 0;
        $appleCount = 0;
        $androidCount = 0;
        $amcobCount = 0;

        foreach ($accounts as $account) {
            $addedBy = strtolower($account->added_by ?? '');
            if ($addedBy === 'web') {
                $webCount += (int)$account->count;
            } elseif ($addedBy === 'admin') {
                $adminCount += (int)$account->count;
            } elseif ($addedBy === 'apple') {
                $appleCount += (int)$account->count;
            } elseif (in_array($addedBy, ['google', 'android'])) {
                $androidCount += (int)$account->count;
            } elseif (in_array($addedBy, ['amcob-api', 'amcob'])) {
                $amcobCount += (int)$account->count;
            }
        }

        // Return in specific order: Web, iOS, Android, Amcob API, Admin
        return response()->json([
            'labels' => ['Web', 'iOS', 'Android', 'Amcob API', 'Admin'],
            'data' => [$webCount, $appleCount, $androidCount, $amcobCount, $adminCount]
        ]);
    }

    /**
     * Show all products and services
     */
    public function showProductsServices(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check if user has view permission
        if (!$isAdmin && (!$user || !$user->hasPermission('products-services.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all'); // all, products, services, deleted
        
        $productsQuery = Product::with('user');
        $servicesQuery = Service::with('user');
        $deletedProductsQuery = Product::onlyTrashed()->with('user');
        $deletedServicesQuery = Service::onlyTrashed()->with('user');
        
        $productsCount = $productsQuery->count();
        $servicesCount = $servicesQuery->count();
        $deletedProductsCount = $deletedProductsQuery->count();
        $deletedServicesCount = $deletedServicesQuery->count();
        $allCount = $productsCount + $servicesCount;
        $deletedCount = $deletedProductsCount + $deletedServicesCount;
        
        $counts = [
            'all' => $allCount,
            'products' => $productsCount,
            'services' => $servicesCount,
            'deleted' => $deletedCount,
        ];
        
        // Get data based on filter
        if ($filter === 'products') {
            $items = $productsQuery->orderByDesc('id')->get();
            // Add type indicator for products
            $items->each(function($item) {
                $item->item_type = 'product';
            });
        } elseif ($filter === 'services') {
            $items = $servicesQuery->orderByDesc('id')->get();
            // Add type indicator for services
            $items->each(function($item) {
                $item->item_type = 'service';
            });
        } elseif ($filter === 'deleted') {
            // Show only deleted items
            $deletedProducts = $deletedProductsQuery->orderByDesc('id')->get();
            $deletedServices = $deletedServicesQuery->orderByDesc('id')->get();
            
            // Add type indicator
            $deletedProducts->each(function($item) {
                $item->item_type = 'product';
            });
            $deletedServices->each(function($item) {
                $item->item_type = 'service';
            });
            
            // Merge both collections and sort by ID descending
            $items = $deletedProducts->concat($deletedServices)->sortByDesc('id')->values();
        } else {
            // Combine products and services for "All" tab
            $products = $productsQuery->orderByDesc('id')->get();
            $services = $servicesQuery->orderByDesc('id')->get();
            
            // Add type indicator to each item
            $products->each(function($item) {
                $item->item_type = 'product';
            });
            $services->each(function($item) {
                $item->item_type = 'service';
            });
            
            // Merge both collections and sort by ID descending
            $items = $products->concat($services)->sortByDesc('id')->values();
        }
        
        return view('admin.products-services.index', compact('items', 'counts', 'filter'));
    }
}
