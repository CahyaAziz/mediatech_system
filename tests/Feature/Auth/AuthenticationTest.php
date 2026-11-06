<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_user_can_log_in_and_session_is_regenerated(): void
    {
        $user = User::query()->create([
            'name' => 'Demo User',
            'email' => 'demo@meditech.test',
            'password' => Hash::make('StrongPassphrase123!'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'demo@meditech.test',
            'password' => 'StrongPassphrase123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::query()->create([
            'name' => 'Inactive User',
            'email' => 'inactive@meditech.test',
            'password' => Hash::make('StrongPassphrase123!'),
            'is_active' => false,
        ]);

        $this->post(route('login.store'), [
            'email' => 'inactive@meditech.test',
            'password' => 'StrongPassphrase123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}