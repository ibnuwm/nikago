<?php

declare(strict_types=1);

use App\Modules\Payment\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('payments')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [PaymentController::class, 'index'])->name('api.payments.index');
    Route::post('/', [PaymentController::class, 'store'])->name('api.payments.store');

    Route::prefix('{uuid}')->group(function (): void {
        Route::get('/', [PaymentController::class, 'show'])->name('api.payments.show');
        Route::post('pay', [PaymentController::class, 'pay'])->name('api.payments.pay');
        Route::post('refund', [PaymentController::class, 'refund'])->name('api.payments.refund');
    });

    Route::get('invoice/{uuid}', [PaymentController::class, 'invoice'])->name('api.payments.invoice');
});

Route::post('payments/callback/{gateway}', [PaymentController::class, 'callback'])
    ->name('api.payments.callback');
