<?php

declare(strict_types=1);

use App\Modules\Analytics\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('analytics')->group(function (): void {
    Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/invitations', [AnalyticsController::class, 'invitations'])->name('analytics.invitations');
    Route::get('/rsvp', [AnalyticsController::class, 'rsvp'])->name('analytics.rsvp');
    Route::get('/guests', [AnalyticsController::class, 'guests'])->name('analytics.guests');
    Route::get('/vendors', [AnalyticsController::class, 'vendors'])->name('analytics.vendors');
    Route::get('/subscriptions', [AnalyticsController::class, 'subscriptions'])->name('analytics.subscriptions');
    Route::get('/revenue', [AnalyticsController::class, 'revenue'])->name('analytics.revenue');
    Route::get('/traffic', [AnalyticsController::class, 'traffic'])->name('analytics.traffic');
    Route::get('/ai', [AnalyticsController::class, 'ai'])->name('analytics.ai');
    Route::get('/export', [AnalyticsController::class, 'export'])->name('analytics.export');
});
