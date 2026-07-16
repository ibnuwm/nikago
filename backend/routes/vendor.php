<?php

declare(strict_types=1);

use App\Modules\Vendor\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendors')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [VendorController::class, 'index'])->name('api.vendors.index');
    Route::post('/', [VendorController::class, 'store'])->name('api.vendors.store');

    Route::prefix('{vendor}')->group(function (): void {
        Route::get('/', [VendorController::class, 'show'])->name('api.vendors.show');
        Route::put('/', [VendorController::class, 'update'])->name('api.vendors.update');
        Route::delete('/', [VendorController::class, 'destroy'])->name('api.vendors.destroy');
        Route::patch('verify', [VendorController::class, 'verify'])->name('api.vendors.verify');
        Route::patch('activate', [VendorController::class, 'activate'])->name('api.vendors.activate');
        Route::patch('deactivate', [VendorController::class, 'deactivate'])->name('api.vendors.deactivate');
        Route::get('statistics', [VendorController::class, 'statistics'])->name('api.vendors.statistics');

        Route::get('services', [VendorController::class, 'indexServices'])->name('api.vendors.services.index');
        Route::post('services', [VendorController::class, 'storeService'])->name('api.vendors.services.store');
        Route::put('services/{service}', [VendorController::class, 'updateService'])->name('api.vendors.services.update');
        Route::delete('services/{service}', [VendorController::class, 'destroyService'])->name('api.vendors.services.destroy');

        Route::get('galleries', [VendorController::class, 'indexGalleries'])->name('api.vendors.galleries.index');
        Route::post('galleries', [VendorController::class, 'storeGallery'])->name('api.vendors.galleries.store');
        Route::put('galleries/{gallery}', [VendorController::class, 'updateGallery'])->name('api.vendors.galleries.update');
        Route::delete('galleries/{gallery}', [VendorController::class, 'destroyGallery'])->name('api.vendors.galleries.destroy');

        Route::get('portfolios', [VendorController::class, 'indexPortfolios'])->name('api.vendors.portfolios.index');
        Route::post('portfolios', [VendorController::class, 'storePortfolio'])->name('api.vendors.portfolios.store');
        Route::put('portfolios/{portfolio}', [VendorController::class, 'updatePortfolio'])->name('api.vendors.portfolios.update');
        Route::delete('portfolios/{portfolio}', [VendorController::class, 'destroyPortfolio'])->name('api.vendors.portfolios.destroy');

        Route::get('packages', [VendorController::class, 'indexPackages'])->name('api.vendors.packages.index');
        Route::post('packages', [VendorController::class, 'storePackage'])->name('api.vendors.packages.store');
        Route::put('packages/{package}', [VendorController::class, 'updatePackage'])->name('api.vendors.packages.update');
        Route::delete('packages/{package}', [VendorController::class, 'destroyPackage'])->name('api.vendors.packages.destroy');
    });
});
