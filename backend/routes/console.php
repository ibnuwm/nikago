<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Horizon snapshot (metrics)
Schedule::command('horizon:snapshot')->everyFiveMinutes();

// Pulse health checks
Schedule::command('pulse:check')->everyMinute();

// Database cleanup (daily at 03:00)
Schedule::command('model:prune')->dailyAt('03:00');
