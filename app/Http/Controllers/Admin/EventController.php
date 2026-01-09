<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Event;
use App\Services\S3Service;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('id', 'desc')->get();
        return view('admin.events.events', compact('events'));
    }

    public function create()
    {
        return view('admin.events.add-event');
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'event_title' => 'required|string|max:255',
            'event_city' => 'required|string|max:100',
            'event_time' => 'required',
            'event_date' => 'required|date',
            'event_venue' => 'required|string|max:255',
            'event_url' => 'required|url',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $event = $id ? Event::findOrFail($id) : new Event();

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

        $message = $id ? 'Event updated successfully!' : 'Event added successfully!';
        return redirect()->route('admin.events')->with('success', $message);
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit-event', compact('event'));
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if ($event->image) {
            $s3Service = app(S3Service::class);
            $oldPath = $s3Service->extractPathFromUrl($event->image);
            if ($oldPath && str_starts_with($oldPath, 'media/')) {
                $s3Service->deleteMedia($oldPath);
            }
        }
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }
}
