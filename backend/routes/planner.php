<?php

declare(strict_types=1);

use App\Http\Controllers\PlannerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('planner')->group(function (): void {
    Route::get('/', [PlannerController::class, 'index'])
        ->name('planner.index');

    Route::get('/summary', [PlannerController::class, 'summary'])
        ->name('planner.summary');

    Route::get('/progress', [PlannerController::class, 'progress'])
        ->name('planner.progress');

    Route::post('/generate-ai', [PlannerController::class, 'generateAi'])
        ->name('planner.generate-ai');

    Route::get('/export', [PlannerController::class, 'export'])
        ->name('planner.export');
});
