<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchedulerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulerLogController extends Controller
{
    /**
     * Display a listing of scheduler logs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Only admins can access scheduler logs
        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all'); // all, deleted
        
        $allCount = SchedulerLog::count();
        $deletedCount = SchedulerLog::onlyTrashed()->count();
        
        $counts = [
            'all' => $allCount,
            'deleted' => $deletedCount,
        ];
        
        // Get data based on filter (no pagination - DataTable handles it)
        if ($filter === 'deleted') {
            $logs = SchedulerLog::onlyTrashed()
                ->orderByDesc('ran_at')
                ->get();
        } else {
            $logs = SchedulerLog::orderByDesc('ran_at')
                ->get();
        }
        
        return view('admin.scheduler-logs.index', compact('logs', 'counts', 'filter'));
    }

    /**
     * Show detailed view of a scheduler log
     */
    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }
        
        $log = SchedulerLog::withTrashed()->findOrFail($id);
        
        // Process user data for better display
        $userChanges = [];
        if ($log->result_data && isset($log->result_data['platforms'])) {
            $platforms = ['web' => 'Web/Authorize.Net', 'google' => 'Google Play', 'apple' => 'Apple'];
            
            foreach ($log->result_data['platforms'] as $platformKey => $platformData) {
                if (isset($platformData['users'])) {
                    $users = $platformData['users'];
                    
                    // Process renewed users
                    if (!empty($users['renewed'])) {
                        foreach ($users['renewed'] as $userData) {
                            $userChanges[] = [
                                'action' => 'renewed',
                                'platform' => $platforms[$platformKey] ?? ucfirst($platformKey),
                                'user_id' => $userData['user_id'] ?? null,
                                'name' => $userData['name'] ?? 'N/A',
                                'email' => $userData['email'] ?? 'N/A',
                                'renewal_date' => $userData['renewal_date'] ?? null,
                                'subscription_id' => $userData['subscription_id'] ?? null,
                            ];
                        }
                    }
                    
                    // Process updated users
                    if (!empty($users['updated'])) {
                        foreach ($users['updated'] as $userData) {
                            $userChanges[] = [
                                'action' => 'updated',
                                'platform' => $platforms[$platformKey] ?? ucfirst($platformKey),
                                'user_id' => $userData['user_id'] ?? null,
                                'name' => $userData['name'] ?? 'N/A',
                                'email' => $userData['email'] ?? 'N/A',
                                'renewal_date' => $userData['renewal_date'] ?? null,
                                'subscription_id' => $userData['subscription_id'] ?? null,
                            ];
                        }
                    }
                    
                    // Process cancelled users
                    if (!empty($users['cancelled'])) {
                        foreach ($users['cancelled'] as $userData) {
                            $userChanges[] = [
                                'action' => 'cancelled',
                                'platform' => $platforms[$platformKey] ?? ucfirst($platformKey),
                                'user_id' => $userData['user_id'] ?? null,
                                'name' => $userData['name'] ?? 'N/A',
                                'email' => $userData['email'] ?? 'N/A',
                                'reason' => $userData['reason'] ?? 'Cancelled',
                                'subscription_id' => $userData['subscription_id'] ?? null,
                            ];
                        }
                    }
                }
            }
        }
        
        return view('admin.scheduler-logs.show', compact('log', 'userChanges'));
    }

    /**
     * Soft delete a scheduler log
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }
        
        $log = SchedulerLog::findOrFail($id);
        $log->delete();
        
        return redirect()->route('admin.scheduler-logs', ['filter' => 'all'])
            ->with('success', 'Scheduler log deleted successfully.');
    }

    /**
     * Restore a soft-deleted scheduler log
     */
    public function restore($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }
        
        $log = SchedulerLog::onlyTrashed()->findOrFail($id);
        $log->restore();
        
        return redirect()->route('admin.scheduler-logs', ['filter' => 'all'])
            ->with('success', 'Scheduler log restored successfully.');
    }
}
