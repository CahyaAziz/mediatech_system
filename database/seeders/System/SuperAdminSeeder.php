<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL');
        $password = env('SUPER_ADMIN_PASSWORD');

        // Seeder ini opsional. Jika credential belum diatur, akun demo dibuat oleh DemoUserSeeder.
        if (!$email || !$password) {
            return;
        }

        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'name' => env('SUPER_ADMIN_NAME', 'Meditech Super Admin'),
                'password' => Hash::make($password),
                'is_active' => true,
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $userId = (int) DB::table('users')->where('email', $email)->value('user_id');
        $roleId = (int) DB::table('roles')->where('role_name', 'Super Admin')->value('role_id');

        if ($userId && $roleId) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $userId, 'role_id' => $roleId],
                ['assigned_by' => null, 'assigned_at' => now()]
            );
        }
    }
}
