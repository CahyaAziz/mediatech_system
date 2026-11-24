<?php

use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\SupplierController;
use App\Http\Controllers\Finance\BankAccountController;
use App\Http\Controllers\Inventory\ProductCategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
| Master Data — satu halaman bertab (Customers, Suppliers, Products, Kategori,
| Warehouses, Rekening). Seluruh tab memakai permission modul `master_data`.
*/
Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::get('/', fn () => redirect()->route('master-data.customers.index'))
        ->middleware('permission:master_data.read')->name('index');

    $resources = [
        'customers' => [CustomerController::class, 'customer'],
        'suppliers' => [SupplierController::class, 'supplier'],
        'products' => [ProductController::class, 'product'],
        'categories' => [ProductCategoryController::class, 'category'],
        'warehouses' => [WarehouseController::class, 'warehouse'],
        'bank-accounts' => [BankAccountController::class, 'bankAccount'],
    ];

    foreach ($resources as $uri => [$controller, $parameter]) {
        Route::prefix($uri)->name($uri . '.')->controller($controller)->group(function () use ($uri, $parameter) {
            Route::get('/', 'index')->middleware('permission:master_data.read')->name('index');
            Route::get('/create', 'create')->middleware('permission:master_data.create')->name('create');
            Route::post('/', 'store')->middleware('permission:master_data.create')->name('store');
            Route::get('/{' . $parameter . '}/edit', 'edit')->whereNumber($parameter)
                ->middleware('permission:master_data.update')->name('edit');
            Route::put('/{' . $parameter . '}', 'update')->whereNumber($parameter)
                ->middleware('permission:master_data.update')->name('update');

            if ($uri === 'products') {
                // Produk dinonaktifkan, bukan dihapus.
                Route::patch('/{product}/status', 'toggleStatus')->whereNumber('product')
                    ->middleware('permission:master_data.update')->name('status');
            } else {
                Route::delete('/{' . $parameter . '}', 'destroy')->whereNumber($parameter)
                    ->middleware('permission:master_data.soft_delete')->name('destroy');
            }
        });
    }
});
