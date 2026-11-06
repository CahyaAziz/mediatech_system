<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Kode modul menggunakan snake_case untuk database; label UI boleh memakai tanda hubung.
        $modules = [
            'dashboard' => ['read', 'export'],
            'quotations' => ['read', 'create', 'update', 'soft_delete', 'restore', 'permanent_delete', 'approve', 'export'],
            'sales_orders' => ['read', 'create', 'update', 'cancel', 'cancel_override', 'approve', 'export'],
            'invoices' => ['read', 'create', 'update', 'export'],
            'payments' => ['read', 'create', 'update', 'approve', 'post', 'reverse', 'export'],
            'cash_flow' => ['read', 'export'],
            'petty_cash' => ['read', 'create', 'update', 'soft_delete', 'restore', 'permanent_delete', 'approve', 'post', 'reverse', 'export'],
            'inventory' => ['read', 'create', 'update', 'post', 'reverse', 'export'],
            'master_data' => ['read', 'create', 'update', 'soft_delete', 'restore', 'permanent_delete', 'export'],
            'users' => ['read', 'create', 'update', 'export'],
            'cms' => ['read', 'create', 'update', 'soft_delete', 'restore', 'permanent_delete', 'export'],
            'audit' => ['read', 'export'],
            'profit' => ['read', 'export'],
            'commissions' => ['read', 'read_all', 'create', 'update', 'approve', 'export'],
            'role_management' => ['read', 'create', 'update', 'delete', 'export'],
        ];

        $labels = [
            'read' => 'Melihat data', 'read_all' => 'Melihat seluruh data', 'create' => 'Menambah data',
            'update' => 'Mengubah data', 'delete' => 'Menghapus role', 'soft_delete' => 'Menghapus sementara',
            'restore' => 'Memulihkan data', 'permanent_delete' => 'Menghapus permanen', 'cancel' => 'Membatalkan transaksi',
            'cancel_override' => 'Membatalkan di luar grace period', 'post' => 'Memposting transaksi',
            'reverse' => 'Membalik/mengoreksi transaksi', 'approve' => 'Menyetujui atau memvalidasi', 'export' => 'Mengekspor data',
        ];

        foreach ($modules as $module => $operations) {
            foreach ($operations as $operation) {
                $code = $module . '.' . $operation;
                DB::table('permissions')->updateOrInsert(
                    ['permission_code' => $code],
                    [
                        'module_code' => $module,
                        'operation_code' => $operation,
                        'description' => ($labels[$operation] ?? $operation) . ' pada modul ' . ucwords(str_replace('_', ' ', $module)),
                        'is_assignable' => true,
                        'is_destructive_operation' => in_array($operation, ['delete', 'soft_delete', 'permanent_delete', 'cancel', 'cancel_override', 'reverse'], true),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
