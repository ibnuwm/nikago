<?php

declare(strict_types=1);

use App\Modules\Budget\Controllers\BudgetController;
use Illuminate\Support\Facades\Route;

Route::prefix('budgets')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/', [BudgetController::class, 'index'])->name('api.budgets.index');
    Route::post('/', [BudgetController::class, 'store'])->name('api.budgets.store');
    Route::get('overview', [BudgetController::class, 'overview'])->name('api.budgets.overview');

    Route::prefix('{budget}')->group(function (): void {
        Route::get('/', [BudgetController::class, 'show'])->name('api.budgets.show');
        Route::put('/', [BudgetController::class, 'update'])->name('api.budgets.update');
        Route::delete('/', [BudgetController::class, 'destroy'])->name('api.budgets.destroy');
        Route::get('summary', [BudgetController::class, 'summary'])->name('api.budgets.summary');
        Route::post('duplicate', [BudgetController::class, 'duplicate'])->name('api.budgets.duplicate');
        Route::post('recalculate', [BudgetController::class, 'recalculate'])->name('api.budgets.recalculate');

        Route::post('categories', [BudgetController::class, 'storeCategory'])->name('api.budgets.categories.store');
        Route::put('categories/reorder', [BudgetController::class, 'reorderCategories'])->name('api.budgets.categories.reorder');
        Route::put('categories/{category}', [BudgetController::class, 'updateCategory'])->name('api.budgets.categories.update');
        Route::delete('categories/{category}', [BudgetController::class, 'destroyCategory'])->name('api.budgets.categories.destroy');

        Route::get('transactions', [BudgetController::class, 'indexTransactions'])->name('api.budgets.transactions.index');
        Route::post('transactions', [BudgetController::class, 'storeTransaction'])->name('api.budgets.transactions.store');
        Route::put('transactions/{transaction}', [BudgetController::class, 'updateTransaction'])->name('api.budgets.transactions.update');
        Route::delete('transactions/{transaction}', [BudgetController::class, 'destroyTransaction'])->name('api.budgets.transactions.destroy');
    });
});
