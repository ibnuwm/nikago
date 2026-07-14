<?php

declare(strict_types=1);

use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('rsvps')->group(function (): void {
    Route::get('/statistics', [RsvpController::class, 'statistics'])
        ->name('rsvps.statistics');

    Route::post('/import', [RsvpController::class, 'import'])
        ->name('rsvps.import');

    Route::get('/export', [RsvpController::class, 'export'])
        ->name('rsvps.export');

    Route::get('/', [RsvpController::class, 'index'])
        ->name('rsvps.index');

    Route::post('/', [RsvpController::class, 'store'])
        ->name('rsvps.store');

    Route::get('/{uuid}', [RsvpController::class, 'show'])
        ->name('rsvps.show');

    Route::put('/{uuid}', [RsvpController::class, 'update'])
        ->name('rsvps.update');

    Route::delete('/{uuid}', [RsvpController::class, 'destroy'])
        ->name('rsvps.destroy');
});
