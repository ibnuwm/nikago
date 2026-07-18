<?php

declare(strict_types=1);

use App\Modules\System\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('settings')->group(function (): void {
    Route::get('/profile', [SettingsController::class, 'getProfile'])->name('settings.profile');
    Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/account', [SettingsController::class, 'getAccount'])->name('settings.account');
    Route::put('/account', [SettingsController::class, 'updateAccount'])->name('settings.account.update');
    Route::put('/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/preferences', [SettingsController::class, 'getPreferences'])->name('settings.preferences');
    Route::put('/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences.update');
    Route::get('/notifications', [SettingsController::class, 'getNotificationPreferences'])->name('settings.notifications');
    Route::put('/notifications', [SettingsController::class, 'updateNotificationPreferences'])->name('settings.notifications.update');
    Route::get('/api-keys', [SettingsController::class, 'listApiKeys'])->name('settings.api-keys');
    Route::post('/api-keys', [SettingsController::class, 'createApiKey'])->name('settings.api-keys.create');
    Route::delete('/api-keys/{uuid}', [SettingsController::class, 'deleteApiKey'])->name('settings.api-keys.delete');
});
