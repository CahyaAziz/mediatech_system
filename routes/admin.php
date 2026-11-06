<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    // Dashboard sementara agar login memiliki tujuan yang valid.
    // Ganti view ini ketika modul Dashboard utama mulai dikerjakan.
    Route::view('/dashboard', 'admin.dashboard.index')
        ->middleware('permission:dashboard.read')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->middleware('permission:users.read')->name('users.index');
        Route::get('/users/export', [UserController::class, 'export'])
            ->middleware(['permission:users.read', 'permission:users.export'])->name('users.export');
        Route::get('/users/create', [UserController::class, 'create'])
            ->middleware(['permission:users.create', 'permission:users.assign_roles'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])
            ->middleware(['permission:users.create', 'permission:users.assign_roles'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->whereNumber('user')->middleware('permission:users.read')->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->whereNumber('user')->middleware('permission:users.update')->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->whereNumber('user')->middleware('permission:users.update')->name('users.update');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])
            ->whereNumber('user')->middleware('permission:users.update')->name('users.status');

        Route::get('/roles', [RoleController::class, 'index'])
            ->middleware('permission:role_management.read')->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])
            ->middleware('permission:role_management.create')->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])
            ->middleware('permission:role_management.create')->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
            ->whereNumber('role')->middleware('permission:role_management.update')->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->whereNumber('role')->middleware('permission:role_management.update')->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->whereNumber('role')->middleware('permission:role_management.delete')->name('roles.destroy');
    });
});