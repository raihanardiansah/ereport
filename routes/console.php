<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Email notification schedules
Schedule::command('email:weekly-digest')
    ->weeklyOn(1, '08:00') // Every Monday at 8 AM
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

Schedule::command('subscriptions:check-expiry')
    ->dailyAt('09:00') // Every day at 9 AM
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

Schedule::command('subscriptions:activate-pending')
    ->dailyAt('00:01') // Every day at 12:01 AM
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

// Auto-escalate pending reports every 15 minutes
Schedule::command('reports:escalate')
    ->everyFifteenMinutes()
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

// Aggregate school statistics on the 1st of each month
Schedule::command('statistics:aggregate')
    ->monthlyOn(1, '02:00')
    ->timezone('Asia/Jakarta');


