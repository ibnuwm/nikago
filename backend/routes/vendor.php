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
    });
});
