<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use App\Models\SupportMessage; // optional: save to DB
use Illuminate\Support\Facades\Mail; // optional: send email
// use App\Mail\SupportReceived; // optional mailable

class SupportController extends Controller
{
    public function create()
    {
        return view('user.feedback'); // view below
    }

    public function store(Request $request)
    {

        dd($request->all());


        // For now just flash a success message and redirect back
        return redirect()->route('user.create')
                         ->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
