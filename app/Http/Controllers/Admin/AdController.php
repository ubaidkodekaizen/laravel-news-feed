<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads\Ad;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    /**
     * Display a listing of the ads.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'active');
        
        $query = Ad::query();
        
        // Apply filter
        switch ($filter) {
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'active':
                $query->where('status', 'Active')->whereNull('deleted_at');
                break;
            case 'inactive':
                $query->where('status', 'Inactive')->whereNull('deleted_at');
                break;
            case 'featured':
                $query->where('featured', true)->whereNull('deleted_at');
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active ads only
                break;
        }
        
        $ads = $query->orderBy('id', 'desc')->get();
        
        // Get counts for tabs
        $baseQuery = Ad::query();
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'active' => (clone $baseQuery)->where('status', 'Active')->whereNull('deleted_at')->count(),
            'inactive' => (clone $baseQuery)->where('status', 'Inactive')->whereNull('deleted_at')->count(),
            'featured' => (clone $baseQuery)->where('featured', true)->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        return view('admin.ads.index', compact('ads', 'counts', 'filter'));
    }

    /**
     * Show the form for creating a new ad.
     */
    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.ads.create');
    }

    /**
     * Store a newly created ad in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:10240', // 10MB max
            'url' => 'nullable|url|max:255',
            'featured' => 'nullable|boolean',
        ]);
        
        $ad = new Ad();
        
        if ($request->hasFile('media')) {
            $s3Service = app(S3Service::class);
            $uploadResult = $s3Service->uploadMedia($request->file('media'), 'ads');
            $mediaPath = $uploadResult['url']; // Store full S3 URL
        } else {
            $mediaPath = null;
        }
        
        $ad->media = $mediaPath;
        $ad->url = $request->url;
        $ad->featured = $request->has('featured') ? (bool)$request->featured : false;
        $ad->status = 'Active'; // Default status
        $ad->save();

        return redirect()->route('admin.ads')->with('success', 'Ad created successfully!');
    }

    /**
     * Show the form for editing the specified ad.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $ad = Ad::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified ad in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:10240', // 10MB max
            'url' => 'nullable|url|max:255',
            'featured' => 'nullable|boolean',
        ]);
        
        $ad = Ad::findOrFail($id);
        
        if ($request->hasFile('media')) {
            $s3Service = app(S3Service::class);
            
            // Delete old media from S3 if exists
            if ($ad->media) {
                $oldPath = $s3Service->extractPathFromUrl($ad->media);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('media'), 'ads');
            $mediaPath = $uploadResult['url']; // Store full S3 URL
        } else {
            $mediaPath = $ad->media;
        }
        
        $ad->media = $mediaPath;
        $ad->url = $request->url;
        $ad->featured = $request->has('featured') ? (bool)$request->featured : false;
        // Status is managed via toggle, don't update here
        $ad->save();

        return redirect()->route('admin.ads')->with('success', 'Ad updated successfully!');
    }

    /**
     * Remove the specified ad from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.delete'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $ad = Ad::findOrFail($id);
        
        // Delete media from S3 if exists
        if ($ad->media) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($ad->media);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        
        $ad->delete();
        return redirect()->back()->with('success', 'Ad deleted successfully!');
    }

    /**
     * Restore the specified ad.
     */
    public function restore($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.restore'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $ad = Ad::onlyTrashed()->findOrFail($id);
        $ad->restore();
        return redirect()->route('admin.ads', ['filter' => 'deleted'])->with('success', 'Ad restored successfully!');
    }

    /**
     * Toggle the featured status of an ad.
     */
    public function toggleFeatured($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.edit'))) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
        
        $ad = Ad::findOrFail($id);
        $ad->featured = !$ad->featured;
        $ad->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Featured status updated successfully!',
            'featured' => $ad->featured
        ]);
    }

    /**
     * Toggle the status of an ad.
     */
    public function toggleStatus($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('ads.edit'))) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
        
        $ad = Ad::findOrFail($id);
        $ad->status = $ad->status === 'Active' ? 'Inactive' : 'Active';
        $ad->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $ad->status
        ]);
    }
}
