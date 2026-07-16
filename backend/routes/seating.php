<?php

declare(strict_types=1);

use App\Http\Controllers\SeatingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('seatings')->group(function (): void {
    Route::get('/', [SeatingController::class, 'index'])->name('seatings.index');
    Route::post('/', [SeatingController::class, 'store'])->name('seatings.store');
    Route::post('/auto-generate', [SeatingController::class, 'autoGenerate'])->name('seatings.auto-generate');
    Route::get('/preview', [SeatingController::class, 'preview'])->name('seatings.preview');
    Route::get('/export', [SeatingController::class, 'export'])->name('seatings.export');
    Route::get('/{uuid}', [SeatingController::class, 'show'])->name('seatings.show');
    Route::put('/{uuid}', [SeatingController::class, 'update'])->name('seatings.update');
    Route::delete('/{uuid}', [SeatingController::class, 'destroy'])->name('seatings.destroy');
    Route::post('/{uuid}/assign', [SeatingController::class, 'assign'])->name('seatings.assign');
    Route::delete('/{uuid}/unassign/{assignmentUuid}', [SeatingController::class, 'unassign'])->name('seatings.unassign');
});
