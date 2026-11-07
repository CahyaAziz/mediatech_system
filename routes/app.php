\<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    Route::view('/dashboard', 'admin.dashboard.index')
        ->middleware('permission:dashboard.read')->name('dashboard');

    require __DIR__ . '/admin.php';
    require __DIR__ . '/finance.php';
});
