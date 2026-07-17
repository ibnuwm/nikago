<?php

declare(strict_types=1);

use App\Http\Controllers\RundownController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('rundowns')->group(function (): void {
    Route::get('/', [RundownController::class, 'index'])->name('rundowns.index');
    Route::post('/', [RundownController::class, 'store'])->name('rundowns.store');
    Route::post('/generate-ai', [RundownController::class, 'generateAi'])->name('rundowns.generate-ai');
    Route::get('/export', [RundownController::class, 'export'])->name('rundowns.export');
    Route::get('/{uuid}', [RundownController::class, 'show'])->name('rundowns.show');
    Route::put('/{uuid}', [RundownController::class, 'update'])->name('rundowns.update');
    Route::patch('/{uuid}/publish', [RundownController::class, 'publish'])->name('rundowns.publish');
    Route::delete('/{uuid}', [RundownController::class, 'destroy'])->name('rundowns.destroy');
});
