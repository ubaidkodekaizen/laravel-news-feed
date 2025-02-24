<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Fruitcake\Cors\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php', // Explicitly adding api.php
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register CORS middleware globally
        // $middleware->append(HandleCors::class);
        // $middleware->group('web', [
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Ensure this is present
        //     \App\Http\Middleware\TrackUserActivity::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
