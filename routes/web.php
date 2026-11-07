<?php

use Illuminate\Support\Facades\Route;

$publicHost = parse_url(env("APP_PUBLIC_URL", "http://meditech.local"), PHP_URL_HOST);
$internalHost = parse_url(env("APP_INTERNAL_URL", "http://app.meditech.local"), PHP_URL_HOST);

// Public domain - company profile (no auth)
Route::domain($publicHost)->group(function () {
    require __DIR__ . "/public.php";
});

// Internal app subdomain - dashboard & operations (auth required)
Route::domain($internalHost)->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    require __DIR__ . "/app.php";
    require __DIR__ . "/auth.php";
});

// Fallback: redirect root of default host to public site
Route::get("/", function () use ($publicHost) {
    return redirect("http://" . $publicHost . "/");
})->name("home.redirect");