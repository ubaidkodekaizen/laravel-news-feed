<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Blog;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all');
        
        $query = Blog::query();
        
        // Apply filter
        switch ($filter) {
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active blogs only
                break;
        }
        
        $blogs = $query->orderBy('id', 'desc')->get();
        
        // Get counts for tabs
        $baseQuery = Blog::query();
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        return view('admin.blogs.blogs', compact('blogs', 'counts', 'filter'));
    }

    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.blogs.add-blog');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);
        
        $blog = new Blog();
        
        if ($request->hasFile('image')) {
            $s3Service = app(S3Service::class);
            $uploadResult = $s3Service->uploadMedia($request->file('image'), 'blog');
            $imagePath = $uploadResult['url']; // Store full S3 URL
        } else {
            $imagePath = null;
        }
        
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->content = $request->content;
        $blog->image = $imagePath;
        $blog->save();

        return redirect()->route('admin.blogs')->with('success', 'Blog created successfully!');
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $blog = Blog::withTrashed()->findOrFail($id);
        return view('admin.blogs.view-blog', compact('blog'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit-blog', compact('blog'));
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);
        
        $blog = Blog::findOrFail($id);
        
        if ($request->hasFile('image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($blog->image) {
                $oldPath = $s3Service->extractPathFromUrl($blog->image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('image'), 'blog');
            $imagePath = $uploadResult['url']; // Store full S3 URL
        } else {
            $imagePath = $blog->image;
        }
        
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);
        $blog->content = $request->content;
        $blog->image = $imagePath;
        $blog->save();

        return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.delete'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $blog = Blog::findOrFail($id);
        
        // Delete image from S3 if exists
        if ($blog->image) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($blog->image);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        
        $blog->delete();
        return redirect()->back()->with('success', 'Blog deleted successfully!');
    }
    
    public function restore($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('blogs.restore'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();
        return redirect()->route('admin.blogs', ['filter' => 'deleted'])->with('success', 'Blog restored successfully!');
    }
}
