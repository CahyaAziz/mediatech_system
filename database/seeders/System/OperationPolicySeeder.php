<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationPolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            ['policy_code' => 'PETTY_CASH_EDIT', 'module_code' => 'petty_cash', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'update', 'grace_period_minutes' => 120, 'retention_days' => 0, 'requires_reason' => false, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'PETTY_CASH_SOFT_DELETE', 'module_code' => 'petty_cash', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 120, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'PETTY_CASH_PERMANENT_DELETE', 'module_code' => 'petty_cash', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'permanent_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'PETTY_CASH_RESTORE', 'module_code' => 'petty_cash', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'restore', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => false, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'SALES_ORDER_CANCEL', 'module_code' => 'sales_orders', 'entity_name' => 'sales_orders', 'operation_code' => 'cancel', 'grace_period_minutes' => 30, 'retention_days' => 0, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'SALES_ORDER_CANCEL_OVERRIDE', 'module_code' => 'sales_orders', 'entity_name' => 'sales_orders', 'operation_code' => 'cancel_override', 'grace_period_minutes' => 0, 'retention_days' => 0, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'MASTER_DATA_SOFT_DELETE', 'module_code' => 'master_data', 'entity_name' => 'master_data', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'CMS_SOFT_DELETE', 'module_code' => 'cms', 'entity_name' => 'cms_pages', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'QUOTATION_SOFT_DELETE', 'module_code' => 'quotations', 'entity_name' => 'quotations', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'GENERAL_TRASH_RETENTION', 'module_code' => 'system', 'entity_name' => 'eligible_records', 'operation_code' => 'permanent_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
        ];

        foreach ($policies as $policy) {
            DB::table('operation_policies')->updateOrInsert(
                ['policy_code' => $policy['policy_code']],
                [...$policy, 'is_active' => true, 'updated_by' => null, 'updated_at' => now()]
            );
        }
    }
}
