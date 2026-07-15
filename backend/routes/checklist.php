<?php

declare(strict_types=1);

use App\Http\Controllers\ChecklistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('checklists')->group(function (): void {
    Route::get('/', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::post('/', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::post('/generate-ai', [ChecklistController::class, 'generateAi'])->name('checklists.generate-ai');
    Route::get('/{uuid}', [ChecklistController::class, 'show'])->name('checklists.show');
    Route::put('/{uuid}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/{uuid}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
    Route::post('/{uuid}/complete', [ChecklistController::class, 'complete'])->name('checklists.complete');
    Route::post('/{uuid}/uncomplete', [ChecklistController::class, 'uncomplete'])->name('checklists.uncomplete');
    Route::patch('/{uuid}/reorder', [ChecklistController::class, 'reorder'])->name('checklists.reorder');
    Route::post('/{uuid}/duplicate', [ChecklistController::class, 'duplicate'])->name('checklists.duplicate');
});
