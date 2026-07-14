<?php

declare(strict_types=1);

use App\Http\Controllers\WeddingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('weddings')->group(function (): void {
    Route::get('/', [WeddingController::class, 'index'])
        ->name('weddings.index');

    Route::post('/', [WeddingController::class, 'store'])
        ->name('weddings.store');

    Route::get('/{uuid}', [WeddingController::class, 'show'])
        ->name('weddings.show');

    Route::put('/{uuid}', [WeddingController::class, 'update'])
        ->name('weddings.update');

    Route::delete('/{uuid}', [WeddingController::class, 'destroy'])
        ->name('weddings.destroy');

    Route::patch('/{uuid}/publish', [WeddingController::class, 'publish'])
        ->name('weddings.publish');

    Route::patch('/{uuid}/archive', [WeddingController::class, 'archive'])
        ->name('weddings.archive');
});
