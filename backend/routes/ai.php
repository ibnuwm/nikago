<?php

declare(strict_types=1);

use App\Modules\AI\Controllers\AiController;
use Illuminate\Support\Facades\Route;

Route::prefix('ai')->middleware(['auth:sanctum'])->group(function (): void {
    Route::post('chat', [AiController::class, 'chat'])->name('api.ai.chat');
    Route::post('story', [AiController::class, 'story'])->name('api.ai.story');
    Route::post('invitation', [AiController::class, 'invitation'])->name('api.ai.invitation');
    Route::post('checklist', [AiController::class, 'checklist'])->name('api.ai.checklist');
    Route::post('budget', [AiController::class, 'budget'])->name('api.ai.budget');
    Route::post('timeline', [AiController::class, 'timeline'])->name('api.ai.timeline');
    Route::post('rundown', [AiController::class, 'rundown'])->name('api.ai.rundown');
    Route::post('caption', [AiController::class, 'caption'])->name('api.ai.caption');
    Route::post('vendor-recommendation', [AiController::class, 'vendorRecommendation'])->name('api.ai.vendor-recommendation');
    Route::get('history', [AiController::class, 'history'])->name('api.ai.history');
    Route::get('models', [AiController::class, 'models'])->name('api.ai.models');
    Route::get('usage', [AiController::class, 'usage'])->name('api.ai.usage');
});
