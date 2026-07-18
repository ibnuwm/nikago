<?php

declare(strict_types=1);

use App\Modules\Notification\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::get('unread', [NotificationController::class, 'unread'])->name('api.notifications.unread');

    Route::prefix('{uuid}')->group(function (): void {
        Route::patch('read', [NotificationController::class, 'markAsRead'])->name('api.notifications.mark-as-read');
        Route::delete('/', [NotificationController::class, 'destroy'])->name('api.notifications.destroy');
    });

    Route::patch('read-all', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-as-read');
});

Route::prefix('notification-templates')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [NotificationController::class, 'templates'])->name('api.notification-templates.index');
    Route::post('/', [NotificationController::class, 'storeTemplate'])->name('api.notification-templates.store');

    Route::prefix('{uuid}')->group(function (): void {
        Route::put('/', [NotificationController::class, 'updateTemplate'])->name('api.notification-templates.update');
        Route::delete('/', [NotificationController::class, 'destroyTemplate'])->name('api.notification-templates.destroy');
    });
});
