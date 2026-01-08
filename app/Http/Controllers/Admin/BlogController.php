<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Blog;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        return view('admin.blogs.blogs', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.add-blog');
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);
        
        if ($id) {
            $blog = Blog::findOrFail($id);
        } else {
            $blog = new Blog();
        }
        
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

        $message = $id ? 'Blog updated successfully!' : 'Blog created successfully!';
        return redirect()->route('admin.blogs')->with('success', $message);
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit-blog', compact('blog'));
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return redirect()->route('admin.blogs')->with('success', 'Blog deleted successfully!');
    }
}
