<?php

declare(strict_types=1);

use App\Modules\Marketplace\Controllers\MarketplaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('marketplace')->group(function (): void {
    Route::get('vendors', [MarketplaceController::class, 'index'])->name('api.marketplace.index');
    Route::get('vendors/{uuid}', [MarketplaceController::class, 'show'])->name('api.marketplace.show');
    Route::get('search', [MarketplaceController::class, 'search'])->name('api.marketplace.search');
    Route::get('categories', [MarketplaceController::class, 'categories'])->name('api.marketplace.categories');
    Route::get('popular', [MarketplaceController::class, 'popular'])->name('api.marketplace.popular');
    Route::get('recommended', [MarketplaceController::class, 'recommended'])->name('api.marketplace.recommended');
    Route::get('featured', [MarketplaceController::class, 'featured'])->name('api.marketplace.featured');

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::get('wishlists', [MarketplaceController::class, 'wishlists'])->name('api.marketplace.wishlists');
        Route::post('wishlist', [MarketplaceController::class, 'addWishlist'])->name('api.marketplace.wishlist.add');
        Route::delete('wishlist/{wishlist}', [MarketplaceController::class, 'removeWishlist'])->name('api.marketplace.wishlist.remove');
        Route::post('compare', [MarketplaceController::class, 'compare'])->name('api.marketplace.compare');
    });
});
