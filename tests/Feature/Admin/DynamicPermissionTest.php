<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DynamicPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_is_read_from_role_permission_pivot(): void
    {
        $user = User::query()->create([
            'name' => 'Role Test', 'email' => 'role@test.local',
            'password' => Hash::make('StrongPassphrase123!'), 'is_active' => true,
        ]);
        $role = Role::query()->create([
            'role_name' => 'Operations Tester', 'description' => 'Role untuk pengujian',
            'is_system_role' => false, 'is_super_admin' => false, 'is_active' => true,
        ]);
        $permission = Permission::query()->create([
            'permission_code' => 'users.read', 'module_code' => 'users', 'operation_code' => 'read',
            'description' => 'Membaca user', 'is_assignable' => true,
            'is_destructive_operation' => false, 'is_active' => true,
        ]);

        $user->roles()->attach($role->role_id, ['assigned_at' => now()]);
        $role->permissions()->attach($permission->permission_id, ['granted_at' => now()]);

        $this->assertTrue($user->fresh()->can('users.read'));
        $this->assertFalse($user->fresh()->can('users.update'));
    }
    public function test_user_assignment_permission_is_separate_from_user_update_permission(): void
    {
        $user = User::query()->create([
            'name' => 'Profile Editor', 'email' => 'profile-editor@test.local',
            'password' => Hash::make('StrongPassphrase123!'), 'is_active' => true,
        ]);
        $role = Role::query()->create([
            'role_name' => 'Profile Editor Role', 'description' => 'Can update profile only',
            'is_system_role' => false, 'is_super_admin' => false, 'is_active' => true,
        ]);
        $permission = Permission::query()->create([
            'permission_code' => 'users.update', 'module_code' => 'users', 'operation_code' => 'update',
            'description' => 'Mengubah profil pengguna', 'is_assignable' => true,
            'is_destructive_operation' => false, 'is_active' => true,
        ]);

        $user->roles()->attach($role->role_id, ['assigned_at' => now()]);
        $role->permissions()->attach($permission->permission_id, ['granted_at' => now()]);

        $this->assertTrue($user->fresh()->can('users.update'));
        $this->assertFalse($user->fresh()->can('users.assign_roles'));
    }

}