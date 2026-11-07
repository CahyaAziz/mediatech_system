<?php

use App\Http\Controllers\Finance\CashFlowController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    Route::prefix('cash-flow')->name('cash-flow.')->group(function () {
        Route::get('/', [CashFlowController::class, 'index'])
            ->middleware('permission:cash_flow.read')
            ->name('index');

        Route::get('/export', [CashFlowController::class, 'export'])
            ->middleware(['permission:cash_flow.read', 'permission:cash_flow.export'])
            ->name('export');
    });
});