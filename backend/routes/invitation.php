<?php

declare(strict_types=1);

use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('invitations')->group(function (): void {
    Route::get('/', [InvitationController::class, 'index'])
        ->name('invitations.index');

    Route::post('/', [InvitationController::class, 'store'])
        ->name('invitations.store');

    Route::get('/{uuid}', [InvitationController::class, 'show'])
        ->name('invitations.show');

    Route::put('/{uuid}', [InvitationController::class, 'update'])
        ->name('invitations.update');

    Route::delete('/{uuid}', [InvitationController::class, 'destroy'])
        ->name('invitations.destroy');

    Route::patch('/{uuid}/publish', [InvitationController::class, 'publish'])
        ->name('invitations.publish');

    Route::patch('/{uuid}/draft', [InvitationController::class, 'draft'])
        ->name('invitations.draft');

    Route::post('/{uuid}/duplicate', [InvitationController::class, 'duplicate'])
        ->name('invitations.duplicate');

    Route::get('/{uuid}/preview', [InvitationController::class, 'preview'])
        ->name('invitations.preview');
});
