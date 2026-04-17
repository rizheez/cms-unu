<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sitemap:generate')
    ->dailyAt('02:00')
    ->withoutOverlapping();

Schedule::command('backup:run --disable-notifications')
    ->dailyAt('02:30')
    ->withoutOverlapping();

Schedule::command('backup:clean --disable-notifications')
    ->weeklyOn(1, '03:00')
    ->withoutOverlapping();
