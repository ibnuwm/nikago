<?php

declare(strict_types=1);

use App\Http\Controllers\TimelineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('timelines')->group(function (): void {
    Route::get('/', [TimelineController::class, 'index'])->name('timelines.index');
    Route::post('/', [TimelineController::class, 'store'])->name('timelines.store');
    Route::post('/generate-ai', [TimelineController::class, 'generateAi'])->name('timelines.generate-ai');
    Route::get('/{uuid}', [TimelineController::class, 'show'])->name('timelines.show');
    Route::put('/{uuid}', [TimelineController::class, 'update'])->name('timelines.update');
    Route::delete('/{uuid}', [TimelineController::class, 'destroy'])->name('timelines.destroy');
    Route::patch('/{uuid}/complete', [TimelineController::class, 'complete'])->name('timelines.complete');
    Route::post('/{uuid}/complete-task', [TimelineController::class, 'completeTask'])->name('timelines.complete-task');
    Route::post('/{uuid}/uncomplete-task', [TimelineController::class, 'uncompleteTask'])->name('timelines.uncomplete-task');
    Route::patch('/{uuid}/reorder', [TimelineController::class, 'reorder'])->name('timelines.reorder');
    Route::post('/{uuid}/sync-google-calendar', [TimelineController::class, 'syncGoogleCalendar'])->name('timelines.sync-google-calendar');
});
