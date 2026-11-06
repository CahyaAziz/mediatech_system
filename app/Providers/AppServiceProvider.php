<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register binding tambahan di sini bila dibutuhkan.
    }

    public function boot(): void
    {
        // Permission dibaca dari database setiap pemeriksaan. Permission baru
        // dapat langsung dipakai tanpa daftar Gate yang ditulis manual.
        Gate::before(function (?User $user, string $ability) {
            if (!$user) {
                return null;
            }

            if (!$user->is_active) {
                return false;
            }

            if ($user->isSuperAdmin()) {
                return true;
            }

            $isDynamicPermission = Permission::query()
                ->where('permission_code', $ability)
                ->where('is_active', true)
                ->exists();

            return $isDynamicPermission ? $user->hasPermissionTo($ability) : null;
        });

        RateLimiter::for('login', function (Request $request) {
            $email = Str::transliterate(Str::lower(trim((string) $request->input('email'))));

            return [
                Limit::perMinute(5)->by('login:' . $email . '|' . $request->ip()),
                Limit::perMinute(30)->by('login-ip:' . $request->ip()),
            ];
        });

        RateLimiter::for('password-reset', function (Request $request) {
            $email = Str::transliterate(Str::lower(trim((string) $request->input('email'))));

            return [
                Limit::perMinute(3)->by('password-reset:' . $email . '|' . $request->ip()),
                Limit::perMinute(10)->by('password-reset-ip:' . $request->ip()),
            ];
        });
    }
}
