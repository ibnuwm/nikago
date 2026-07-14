<?php

declare(strict_types=1);

use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('templates')->group(function (): void {
    Route::get('/', [TemplateController::class, 'index'])
        ->name('templates.index');

    Route::get('/categories', [TemplateController::class, 'categories'])
        ->name('templates.categories');

    Route::get('/premium', [TemplateController::class, 'premium'])
        ->name('templates.premium');

    Route::get('/{uuid}', [TemplateController::class, 'show'])
        ->name('templates.show');

    Route::post('/{uuid}/use', [TemplateController::class, 'use'])
        ->name('templates.use');

    Route::post('/{uuid}/favorite', [TemplateController::class, 'favorite'])
        ->name('templates.favorite');

    Route::delete('/{uuid}/favorite', [TemplateController::class, 'unfavorite'])
        ->name('templates.unfavorite');
});
