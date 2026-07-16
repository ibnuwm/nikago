<?php

declare(strict_types=1);

use App\Modules\Review\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('reviews')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [ReviewController::class, 'index'])->name('api.reviews.index');
    Route::post('/', [ReviewController::class, 'store'])->name('api.reviews.store');

    Route::prefix('{uuid}')->group(function (): void {
        Route::get('/', [ReviewController::class, 'show'])->name('api.reviews.show');
        Route::put('/', [ReviewController::class, 'update'])->name('api.reviews.update');
        Route::delete('/', [ReviewController::class, 'destroy'])->name('api.reviews.destroy');
        Route::post('reply', [ReviewController::class, 'reply'])->name('api.reviews.reply');
        Route::post('report', [ReviewController::class, 'report'])->name('api.reviews.report');
    });
});

Route::get('vendors/{vendorUuid}/reviews', [ReviewController::class, 'vendorReviews'])
    ->name('api.vendors.reviews');
