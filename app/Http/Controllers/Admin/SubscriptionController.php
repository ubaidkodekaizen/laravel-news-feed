<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('user')
            ->where(function($query) {
                $query->whereNotIn('platform', ['DB', 'Amcob'])
                      ->orWhereNull('platform');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.subscriptions', compact('subscriptions'));
    }
}
