<?php

namespace Database\Seeders\System;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        $permissions = DB::table('permissions')->get()->keyBy('permission_code');
        $allPermissionCodes = $permissions->keys()->all();

        $grant = [
            'Super Admin' => $allPermissionCodes,
            'Sales' => [
                'dashboard.read', 'dashboard.export',
                'quotations.read', 'quotations.create', 'quotations.update', 'quotations.soft_delete', 'quotations.restore', 'quotations.export',
                'sales_orders.read', 'sales_orders.create', 'sales_orders.update', 'sales_orders.cancel', 'sales_orders.export',
                'master_data.read', 'master_data.create', 'master_data.update',
                'commissions.read',
            ],
            'Manager' => [
                'dashboard.read', 'dashboard.export',
                'quotations.read', 'quotations.create', 'quotations.update', 'quotations.approve', 'quotations.export',
                'sales_orders.read', 'sales_orders.create', 'sales_orders.update', 'sales_orders.export',
                'master_data.read', 'master_data.create', 'master_data.update', 'master_data.export',
                'audit.read', 'audit.export', 'profit.read', 'profit.export',
                'commissions.read', 'commissions.read_all', 'commissions.export',
            ],
            'Warehouse' => [
                'dashboard.read', 'dashboard.export',
                'sales_orders.read',
                'inventory.read', 'inventory.create', 'inventory.update', 'inventory.post', 'inventory.export',
                'master_data.read', 'master_data.create', 'master_data.update',
            ],
            'Finance' => [
                'dashboard.read', 'dashboard.export',
                'quotations.read', 'quotations.export', 'sales_orders.read', 'sales_orders.export',
                'invoices.read', 'invoices.create', 'invoices.update', 'invoices.export',
                'payments.read', 'payments.create', 'payments.update', 'payments.approve', 'payments.post', 'payments.export',
                'cash_flow.read', 'cash_flow.export',
                'petty_cash.read', 'petty_cash.create', 'petty_cash.update', 'petty_cash.soft_delete', 'petty_cash.export',
                'master_data.read', 'master_data.create', 'master_data.update',
            ],
            'Finance Manager/Director' => [
                'dashboard.read', 'dashboard.export',
                'quotations.read', 'quotations.export', 'sales_orders.read', 'sales_orders.export',
                'invoices.read', 'invoices.create', 'invoices.update', 'invoices.export',
                'payments.read', 'payments.create', 'payments.update', 'payments.approve', 'payments.post', 'payments.export',
                'cash_flow.read', 'cash_flow.export',
                'petty_cash.read', 'petty_cash.create', 'petty_cash.update', 'petty_cash.soft_delete', 'petty_cash.restore', 'petty_cash.approve', 'petty_cash.post', 'petty_cash.export',
                'master_data.read', 'master_data.create', 'master_data.update', 'master_data.export',
                'audit.read', 'audit.export', 'profit.read', 'profit.export',
                'commissions.read', 'commissions.read_all', 'commissions.export',
            ],
            'Admin CMS' => [
                'cms.read', 'cms.create', 'cms.update', 'cms.soft_delete', 'cms.restore', 'cms.export',
            ],
        ];

        foreach ($grant as $roleName => $codes) {
            $roleId = $this->roleId($roleName);
            foreach (array_unique($codes) as $code) {
                if (!$permissions->has($code)) {
                    continue;
                }
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permissions[$code]->permission_id],
                    ['granted_by' => null, 'granted_at' => now()]
                );
            }
        }
    }
}
