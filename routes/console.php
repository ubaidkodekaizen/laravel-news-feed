<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule unified subscription sync for all platforms (Web/Authorize.Net, Google Play, Apple)
Schedule::command('subscriptions:sync-all')
    ->daily()
    ->at('02:00')
    ->timezone('America/New_York'); // Adjust timezone as needed

// Renewal reminder scheduler - Currently disabled
// Schedule::command('subscriptions:send-renewal-reminders')
//     ->daily()
//     ->at('02:03')
//     ->timezone('America/New_York');
