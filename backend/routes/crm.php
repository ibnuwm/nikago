<?php

declare(strict_types=1);

use App\Modules\CRM\Controllers\CrmController;
use Illuminate\Support\Facades\Route;

Route::prefix('crm')->middleware(['auth:sanctum'])->group(function (): void {
    Route::get('leads', [CrmController::class, 'index'])->name('api.crm.leads.index');
    Route::post('leads', [CrmController::class, 'store'])->name('api.crm.leads.store');

    Route::prefix('leads/{uuid}')->group(function (): void {
        Route::get('/', [CrmController::class, 'show'])->name('api.crm.leads.show');
        Route::put('/', [CrmController::class, 'update'])->name('api.crm.leads.update');
        Route::delete('/', [CrmController::class, 'destroy'])->name('api.crm.leads.destroy');
        Route::patch('assign', [CrmController::class, 'assign'])->name('api.crm.leads.assign');
        Route::patch('move-stage', [CrmController::class, 'moveStage'])->name('api.crm.leads.move-stage');
        Route::post('follow-up', [CrmController::class, 'followUp'])->name('api.crm.leads.follow-up');
    });

    Route::get('pipelines', [CrmController::class, 'pipelines'])->name('api.crm.pipelines');
    Route::get('statistics', [CrmController::class, 'statistics'])->name('api.crm.statistics');
});
