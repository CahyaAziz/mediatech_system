<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestPasswordResetLinkRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(RequestPasswordResetLinkRequest $request): RedirectResponse
    {
        // Jangan mengungkap apakah email terdaftar atau akun sedang aktif.
        $userIsActive = User::query()
            ->where('email', mb_strtolower(trim($request->validated('email'))))
            ->where('is_active', true)
            ->exists();

        if ($userIsActive) {
            Password::sendResetLink($request->only('email'));
        }

        return back()->with('status', 'Jika email terdaftar dan akun aktif, instruksi reset kata sandi akan dikirim ke email tersebut.');
    }
}
