<?php

declare(strict_types=1);

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('guests')->group(function (): void {
    Route::get('/', [GuestController::class, 'index'])
        ->name('guests.index');

    Route::post('/', [GuestController::class, 'store'])
        ->name('guests.store');

    Route::get('/export', [GuestController::class, 'export'])
        ->name('guests.export');

    Route::post('/import', [GuestController::class, 'import'])
        ->name('guests.import');

    Route::post('/send-invitation', [GuestController::class, 'sendInvitation'])
        ->name('guests.send-invitation');

    Route::post('/send-reminder', [GuestController::class, 'sendReminder'])
        ->name('guests.send-reminder');

    Route::get('/check-in-history', [GuestController::class, 'checkInHistory'])
        ->name('guests.check-in-history');

    Route::patch('/{uuid}/check-in', [GuestController::class, 'checkIn'])
        ->name('guests.check-in');

    Route::get('/{uuid}', [GuestController::class, 'show'])
        ->name('guests.show');

    Route::put('/{uuid}', [GuestController::class, 'update'])
        ->name('guests.update');

    Route::delete('/{uuid}', [GuestController::class, 'destroy'])
        ->name('guests.destroy');
});
