<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            $this->command?->warn('DemoUserSeeder dilewati. Seeder akun demo hanya boleh dijalankan di local/testing.');
            return;
        }

        $password = env('DEMO_USER_PASSWORD', 'ChangeMe123!');
        $users = [
            ['name' => 'Mirachel Cindejona', 'email' => 'mirachel.cindejona@meditech.co.id', 'role' => 'Super Admin', 'active' => true, 'last_login_at' => now()->subMinutes(2)],
            ['name' => 'Rafli Ahmad', 'email' => 'rafli.ahmad@meditech.co.id', 'role' => 'Sales', 'active' => true, 'last_login_at' => now()->subMinutes(10)],
            ['name' => 'Regina Nur Fadhilah', 'email' => 'regina.fadhilah@meditech.co.id', 'role' => 'Manager', 'active' => true, 'last_login_at' => now()->subMinutes(32)],
            ['name' => 'Dzaky Julian', 'email' => 'dzaky.julian@meditech.co.id', 'role' => 'Warehouse', 'active' => true, 'last_login_at' => now()->subHour()],
            ['name' => 'Ica Apriyanti', 'email' => 'ica.apriyanti@meditech.co.id', 'role' => 'Finance', 'active' => true, 'last_login_at' => now()->subMinutes(18)],
            ['name' => 'Cahya Abdul Aziz', 'email' => 'cahya.aziz@meditech.co.id', 'role' => 'Finance Manager/Director', 'active' => true, 'last_login_at' => now()->subHours(2)],
            ['name' => 'Anaqi Ahza', 'email' => 'anaqi.ahza@meditech.co.id', 'role' => 'Admin CMS', 'active' => true, 'last_login_at' => now()->subDay()],
            ['name' => 'Nugra Hasahatan', 'email' => 'nugra.hasahatan@meditech.co.id', 'role' => 'Sales', 'active' => false, 'last_login_at' => now()->subDays(3)],
        ];

        foreach ($users as $data) {
            DB::table('users')->updateOrInsert(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($password),
                    'is_active' => $data['active'],
                    'last_login_at' => $data['last_login_at'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $userId = $this->userId($data['email']);
            $roleId = $this->roleId($data['role']);

            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $userId, 'role_id' => $roleId],
                ['assigned_by' => null, 'assigned_at' => now()]
            );
        }
    }
}
