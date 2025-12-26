<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule subscription renewal date sync to run daily
Schedule::command('subscriptions:sync-renewal-dates')
    ->daily()
    ->at('02:00')
    ->timezone('America/New_York'); // Adjust timezone as needed
