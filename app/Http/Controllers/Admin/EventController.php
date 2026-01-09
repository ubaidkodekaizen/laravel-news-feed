<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Event;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $filter = $request->get('filter', 'all');
        
        $query = Event::query();
        
        // Apply filter
        switch ($filter) {
            case 'deleted':
                $query->onlyTrashed();
                break;
            case 'all':
            default:
                $query->whereNull('deleted_at'); // Active events only
                break;
        }
        
        $events = $query->orderBy('id', 'desc')->get();
        
        // Get counts for tabs
        $baseQuery = Event::query();
        $counts = [
            'all' => (clone $baseQuery)->whereNull('deleted_at')->count(),
            'deleted' => (clone $baseQuery)->onlyTrashed()->count(),
        ];
        
        return view('admin.events.events', compact('events', 'counts', 'filter'));
    }

    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.events.add-event');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.create'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'event_title' => 'required|string|max:255',
            'event_city' => 'required|string|max:100',
            'event_time' => 'required',
            'event_date' => 'required|date',
            'event_venue' => 'required|string|max:255',
            'event_url' => 'required|url',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $event = new Event();
        $event->title = $request->input('event_title');
        $event->city = $request->input('event_city');
        $event->time = $request->input('event_time');
        $event->date = $request->input('event_date');
        $event->venue = $request->input('event_venue');
        $event->url = $request->input('event_url');

        if ($request->hasFile('event_image')) {
            $s3Service = app(S3Service::class);
            $uploadResult = $s3Service->uploadMedia($request->file('event_image'), 'event');
            $event->image = $uploadResult['url']; // Store full S3 URL
        }

        $event->save();

        return redirect()->route('admin.events')->with('success', 'Event added successfully!');
    }

    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.view'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $event = Event::withTrashed()->findOrFail($id);
        return view('admin.events.view-event', compact('event'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $event = Event::findOrFail($id);
        return view('admin.events.edit-event', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.edit'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'event_title' => 'required|string|max:255',
            'event_city' => 'required|string|max:100',
            'event_time' => 'required',
            'event_date' => 'required|date',
            'event_venue' => 'required|string|max:255',
            'event_url' => 'required|url',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $event = Event::findOrFail($id);
        $event->title = $request->input('event_title');
        $event->city = $request->input('event_city');
        $event->time = $request->input('event_time');
        $event->date = $request->input('event_date');
        $event->venue = $request->input('event_venue');
        $event->url = $request->input('event_url');

        if ($request->hasFile('event_image')) {
            $s3Service = app(S3Service::class);
            
            // Delete old image from S3 if exists
            if ($event->image) {
                $oldPath = $s3Service->extractPathFromUrl($event->image);
                if ($oldPath && str_starts_with($oldPath, 'media/')) {
                    $s3Service->deleteMedia($oldPath);
                }
            }
            
            $uploadResult = $s3Service->uploadMedia($request->file('event_image'), 'event');
            $event->image = $uploadResult['url']; // Store full S3 URL
        }

        $event->save();

        return redirect()->route('admin.events')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.delete'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $event = Event::findOrFail($id);
        if ($event->image) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($event->image);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
    
    public function restore($id)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        
        // Check permission
        if (!$isAdmin && (!$user || !$user->hasPermission('events.restore'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->restore();
        return redirect()->route('admin.events', ['filter' => 'deleted'])->with('success', 'Event restored successfully!');
    }
}
