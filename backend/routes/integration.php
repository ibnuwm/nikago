<?php

declare(strict_types=1);

use App\Modules\Integration\Controllers\IntegrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('integrations')->group(function (): void {
    Route::get('/', [IntegrationController::class, 'index'])->name('integrations.index');
    Route::get('/providers', [IntegrationController::class, 'providers'])->name('integrations.providers');

    Route::post('/google/connect', [IntegrationController::class, 'googleConnect'])->name('integrations.google.connect');
    Route::delete('/google/disconnect', [IntegrationController::class, 'googleDisconnect'])->name('integrations.google.disconnect');

    Route::post('/calendar/connect', [IntegrationController::class, 'calendarConnect'])->name('integrations.calendar.connect');
    Route::delete('/calendar/disconnect', [IntegrationController::class, 'calendarDisconnect'])->name('integrations.calendar.disconnect');

    Route::post('/whatsapp/connect', [IntegrationController::class, 'whatsappConnect'])->name('integrations.whatsapp.connect');
    Route::delete('/whatsapp/disconnect', [IntegrationController::class, 'whatsappDisconnect'])->name('integrations.whatsapp.disconnect');

    Route::get('/webhooks', [IntegrationController::class, 'webhooks'])->name('integrations.webhooks');
    Route::post('/webhooks', [IntegrationController::class, 'storeWebhook'])->name('integrations.webhooks.store');
    Route::delete('/webhooks/{uuid}', [IntegrationController::class, 'deleteWebhook'])->name('integrations.webhooks.delete');

    Route::post('/test', [IntegrationController::class, 'test'])->name('integrations.test');
});
