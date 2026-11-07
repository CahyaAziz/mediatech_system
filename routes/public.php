\<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PublicController::class, 'show'])->name('page.show');

// Redirect login attempts on public domain to internal app
Route::get('/login', function () {
    return redirect(env('APP_INTERNAL_URL', 'http://app.meditech.local') . '/login');
})->name('login.redirect');
