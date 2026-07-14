<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('dashboard')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    Route::get('/statistics', [DashboardController::class, 'statistics'])
        ->name('dashboard.statistics');

    Route::get('/recent-activity', [DashboardController::class, 'recentActivity'])
        ->name('dashboard.recent-activity');

    Route::get('/upcoming-events', [DashboardController::class, 'upcomingEvents'])
        ->name('dashboard.upcoming-events');
});
