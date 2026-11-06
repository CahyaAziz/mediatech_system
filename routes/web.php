<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/admin/user-management', 'admin.users.index')->name('users');