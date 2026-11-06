<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Services\Audit\AuditLogService;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $credentials['is_active'] = true;

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai, atau akun tidak aktif.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();
        $this->audit->record($user, 'LOGIN', 'USERS', $user->user_id, 'Pengguna berhasil login.', [], [
            'email' => $user->email,
            'last_login_at' => $user->last_login_at?->toDateTimeString(),
        ]);

        RateLimiter::clear('login:' . $this->throttleKey($request));

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(\Illuminate\Http\Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            $this->audit->record($user, 'LOGOUT', 'USERS', $user->user_id, 'Pengguna berhasil logout.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Kamu berhasil keluar dari Meditech.');
    }

    private function throttleKey(\Illuminate\Http\Request $request): string
    {
        return Str::transliterate(Str::lower(trim((string) $request->input('email')))) . '|' . $request->ip();
    }
}