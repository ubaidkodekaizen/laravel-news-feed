<?php

namespace App\Http\Controllers;

use App\Models\Content\Blog;
use App\Models\Business\Company;
use App\Models\Content\Event;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        $staticPages = [
            '/' => 1.0,
            '/terms' => 0.5,
            '/privacy-policy' => 0.5,
            '/child-safety-standard' => 0.5,
            '/feed' => 0.7,
            '/search' => 0.7,
            '/industry-experts' => 0.6,
        ];

        foreach ($staticPages as $page => $priority) {
            $sitemap->add(Url::create($page)->setPriority($priority));
        }

        $dynamicModels = [
            'blogs' => Blog::all(),
            'products' => Product::all(),
            'services' => Service::all(),
            'users' => User::whereNotNull('slug')->get(),
            'companies' => Company::whereNotNull('company_slug')->get(),
            'events' => Event::all(),
        ];

        foreach ($dynamicModels as $type => $items) {
            foreach ($items as $item) {
                $url = match ($type) {
                    'blogs' => '/blog/' . $item->slug,
                    'products' => '/products/' . $item->id,
                    'services' => '/services/' . $item->id,
                    'users' => '/user/profile/' . $item->slug,
                    'companies' => '/company/' . $item->company_slug,
                    'events' => '/events/' . $item->id,
                };

                $sitemap->add(
                    Url::create($url)
                        ->setLastModificationDate($item->updated_at ?? now())
                        ->setPriority(0.8)
                );
            }
        }

        return $sitemap->toResponse(request());
    }
}
