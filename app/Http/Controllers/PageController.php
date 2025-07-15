<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;


class PageController extends Controller
{
    public function feed()
    {
        $industries = [
            ['icon' => 'fas fa-laptop-code', 'name' => 'Technology'],
            ['icon' => 'fas fa-heartbeat', 'name' => 'Healthcare'],
            ['icon' => 'fa-solid fa-file-invoice-dollar', 'name' => 'Finance'],
            ['icon' => 'fas fa-shopping-cart', 'name' => 'Retail'],
            ['icon' => 'fas fa-tractor', 'name' => 'Agriculture, Forestry, Fishing and Hunting'],
            ['icon' => 'fa-solid fa-oil-well', 'name' => 'Mining, Quarrying, and Oil and Gas Extraction'],
            ['icon' => 'fas fa-bolt', 'name' => 'Utilities'],
            ['icon' => 'fa-solid fa-person-digging', 'name' => 'Construction'],
            ['icon' => 'fas fa-industry', 'name' => 'Manufacturing'],
            ['icon' => 'fas fa-boxes', 'name' => 'Wholesale Trade'],
            ['icon' => 'fas fa-store', 'name' => 'Retail Trade'],
            ['icon' => 'fas fa-truck', 'name' => 'Transportation and Warehousing'],
            ['icon' => 'fas fa-info-circle', 'name' => 'Information'],
            ['icon' => 'fa-solid fa-hand-holding-dollar', 'name' => 'Finance and Insurance'],
            ['icon' => 'fas fa-home', 'name' => 'Real Estate and Rental and Leasing'],
            ['icon' => 'fas fa-flask', 'name' => 'Professional, Scientific, and Technical Services'],
            ['icon' => 'fas fa-building', 'name' => 'Management of Companies and Enterprises'],
            ['icon' => 'fa-solid fa-user-gear', 'name' => 'Administrative and Support and Waste Management and Remediation Services'],
            ['icon' => 'fas fa-graduation-cap', 'name' => 'Educational Services'],
            ['icon' => 'fas fa-hospital', 'name' => 'Health Care and Social Assistance'],
            ['icon' => 'fas fa-theater-masks', 'name' => 'Arts, Entertainment, and Recreation'],
            ['icon' => 'fas fa-utensils', 'name' => 'Accommodation and Food Services'],
            ['icon' => 'fas fa-cogs', 'name' => 'Other Services (except Public Administration)'],
            ['icon' => 'fas fa-landmark', 'name' => 'Public Administration'],
            ['icon' => 'fa-solid fa-ferry', 'name' => 'Navy'],
            ['icon' => 'fas fa-question-circle', 'name' => 'Other'],
        ];

     
        usort($industries, function ($a, $b) {
            if ($a['name'] === 'Other')
                return 1;
            if ($b['name'] === 'Other')
                return -1;
            return strcmp($a['name'], $b['name']);
        });

        $blogs = Blog::orderByDesc('id')->get();
        $events = Event::orderByDesc('id')->get();
        $products = Product::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        })
            ->orderByDesc('id')
            ->get();

        $services = Service::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        })
            ->orderByDesc('id')
            ->get();

        return view('feed', compact('blogs', 'events', 'products', 'services', 'industries'));
    }

    public function products()
    {
        $products = Product::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        })
            ->orderByDesc('id')
            ->get();
        return view('products', compact('products'));
    }

    public function services()
    {
        $services = Service::whereHas('user', function ($query) {
            $query->where('status', 'complete');
        })
            ->orderByDesc('id')
            ->get();
        return view('services', compact('services'));
    }


    public function industryExperts($industry)
{
    $users = User::where('status', 'complete')
        ->whereHas('company', function ($query) use ($industry) {
            if (strtolower($industry) === 'other') {
                $query->where(function ($q) {
                    $q->whereNull('company_industry')
                      ->orWhere('company_industry', '')
                      ->orWhere('company_industry', 'N/A')
                      ->orWhere('company_industry', 'Other')
                      ->orWhere('company_industry', 'other');
                });
            } else {
                $query->where('company_industry', 'LIKE', "%{$industry}%");
            }
        })
        ->with('company')
        ->get();

    return view('industry', compact('users', 'industry'));
}



}
