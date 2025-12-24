<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a list of user education records.
     */
    public function index()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->get();
        return view('user.user-subscriptions', compact('subscriptions'));
    }


    public function addSubscription()
    {
        return view('user.add-subscription');
    }
}
