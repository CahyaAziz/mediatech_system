<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';

Route::view('/', 'home')->name('home');
Route::view('/admin/user-management', 'admin.users.index')->name('users');