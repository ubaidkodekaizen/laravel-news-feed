<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserOnlineService;

class TrackUserActivity
{
    protected $userOnlineService;

    public function __construct(UserOnlineService $userOnlineService)
    {
        $this->userOnlineService = $userOnlineService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $this->userOnlineService->markUserActive(auth()->id());
        }

        return $next($request);
    }
}