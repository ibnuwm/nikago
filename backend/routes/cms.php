<?php

declare(strict_types=1);

use App\Http\Controllers\Cms\CmsController;
use Illuminate\Support\Facades\Route;

Route::prefix('cms')->middleware('throttle:120,1')->group(function (): void {
    Route::get('/faqs', [CmsController::class, 'faqs'])
        ->name('cms.faqs');

    Route::get('/banners', [CmsController::class, 'banners'])
        ->name('cms.banners');

    Route::get('/pages', [CmsController::class, 'pages'])
        ->name('cms.pages');

    Route::get('/pages/{slug}', [CmsController::class, 'pageBySlug'])
        ->name('cms.pages.show');

    Route::get('/terms', [CmsController::class, 'terms'])
        ->name('cms.terms');

    Route::get('/privacy-policy', [CmsController::class, 'privacyPolicy'])
        ->name('cms.privacy-policy');
});
