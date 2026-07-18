<?php

declare(strict_types=1);

use App\Modules\Subscription\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('subscriptions')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('plans', [SubscriptionController::class, 'index'])->name('api.subscriptions.plans');
    Route::get('current', [SubscriptionController::class, 'current'])->name('api.subscriptions.current');
    Route::post('subscribe', [SubscriptionController::class, 'subscribe'])->name('api.subscriptions.subscribe');
    Route::post('upgrade', [SubscriptionController::class, 'upgrade'])->name('api.subscriptions.upgrade');
    Route::post('downgrade', [SubscriptionController::class, 'downgrade'])->name('api.subscriptions.downgrade');
    Route::post('cancel', [SubscriptionController::class, 'cancel'])->name('api.subscriptions.cancel');
    Route::get('history', [SubscriptionController::class, 'history'])->name('api.subscriptions.history');
    Route::get('features', [SubscriptionController::class, 'features'])->name('api.subscriptions.features');
});
