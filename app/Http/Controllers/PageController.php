<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;

class PageController extends Controller
{
    public function feed()
    {
        $blogs = Blog::orderByDesc('id')->get();
        $events = Event::orderByDesc('id')->get();

        return view('feed', compact('blogs', 'events'));
    }
}
