<?php

declare(strict_types=1);

use App\Modules\Booking\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('bookings')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('calendar', [BookingController::class, 'calendar'])->name('api.bookings.calendar');
    Route::get('history/{uuid}', [BookingController::class, 'history'])->name('api.bookings.history');

    Route::get('/', [BookingController::class, 'index'])->name('api.bookings.index');
    Route::post('/', [BookingController::class, 'store'])->name('api.bookings.store');

    Route::prefix('{uuid}')->group(function (): void {
        Route::get('/', [BookingController::class, 'show'])->name('api.bookings.show');
        Route::put('/', [BookingController::class, 'update'])->name('api.bookings.update');
        Route::patch('confirm', [BookingController::class, 'confirm'])->name('api.bookings.confirm');
        Route::patch('cancel', [BookingController::class, 'cancel'])->name('api.bookings.cancel');
        Route::patch('complete', [BookingController::class, 'complete'])->name('api.bookings.complete');
        Route::post('contract', [BookingController::class, 'contract'])->name('api.bookings.contract');
    });
});
