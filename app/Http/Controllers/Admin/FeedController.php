<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feed\Post;
use App\Models\Feed\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission - all admin users can view feed
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all');
        
        $query = Post::with(['user:id,first_name,last_name,email', 'media'])
            ->withCount(['reactions', 'comments', 'shares']);
        
        // Apply filter
        switch ($filter) {
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active posts only
                break;
        }
        
        $posts = $query->orderBy('id', 'desc')->get();
        
        // Get counts for tabs
        $baseQuery = Post::query();
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        return view('admin.feed.index', compact('posts', 'counts', 'filter'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $post = Post::withTrashed()
            ->withCount([
                'reactions',
                'comments' => function($query) {
                    $query->whereNull('deleted_at'); // Count only active comments
                },
                'shares'
            ])
            ->with([
                'user:id,first_name,last_name,email,phone,photo',
                'media',
                'reactions.user:id,first_name,last_name',
                'comments' => function($query) {
                    $query->withTrashed()
                          ->whereNull('parent_id')
                          ->with(['user:id,first_name,last_name,email,photo', 'replies' => function($q) {
                              $q->withTrashed()
                                ->with('user:id,first_name,last_name,email,photo');
                          }])
                          ->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($id);
        
        return view('admin.feed.show', compact('post'));
    }

    public function deletePost($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.delete'))) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        
        $post = Post::findOrFail($id);
        $post->delete(); // Soft delete
        
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.'
        ]);
    }

    public function restorePost($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.restore'))) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Post restored successfully.'
        ]);
    }

    public function deleteComment($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.delete'))) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        
        $comment = PostComment::findOrFail($id);
        $comment->delete(); // Soft delete
        
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function restoreComment($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('feed.restore'))) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        
        $comment = PostComment::onlyTrashed()->findOrFail($id);
        $comment->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment restored successfully.'
        ]);
    }
}
