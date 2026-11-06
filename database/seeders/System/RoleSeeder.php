<?php

namespace Database\Seeders\System;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        $roles = [
            ['role_name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh modul dan pengaturan sistem.', 'is_system_role' => true, 'is_super_admin' => true],
            ['role_name' => 'Sales', 'description' => 'Mengelola quotation, sales order, dan data sales miliknya.', 'is_system_role' => true, 'is_super_admin' => false],
            ['role_name' => 'Manager', 'description' => 'Memantau aktivitas penjualan dan melakukan approval terkait.', 'is_system_role' => true, 'is_super_admin' => false],
            ['role_name' => 'Warehouse', 'description' => 'Mengelola persediaan, penerimaan, dan pengeluaran barang.', 'is_system_role' => true, 'is_super_admin' => false],
            ['role_name' => 'Finance', 'description' => 'Mengelola invoice, payment, petty cash, dan cash flow.', 'is_system_role' => true, 'is_super_admin' => false],
            ['role_name' => 'Finance Manager/Director', 'description' => 'Mengawasi proses keuangan, approval, dan laporan.', 'is_system_role' => true, 'is_super_admin' => false],
            ['role_name' => 'Admin CMS', 'description' => 'Mengelola konten company profile dan media.', 'is_system_role' => true, 'is_super_admin' => false],
        ];

        foreach ($roles as $role) {
            $this->upsertRow('roles', ['role_name' => $role['role_name']], [
                ...$role,
                'is_active' => true,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
