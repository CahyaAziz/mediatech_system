<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationPolicySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $policies = [
            ['policy_code' => 'PETTY_CASH_EDIT', 'module_code' => 'finance', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'update', 'grace_period_minutes' => 120, 'retention_days' => 0, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'PETTY_CASH_SOFT_DELETE', 'module_code' => 'finance', 'entity_name' => 'petty_cash_transactions', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 120, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'SO_CANCEL', 'module_code' => 'sales', 'entity_name' => 'sales_orders', 'operation_code' => 'cancel', 'grace_period_minutes' => 30, 'retention_days' => 0, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'SO_CANCEL_OVERRIDE', 'module_code' => 'sales', 'entity_name' => 'sales_orders', 'operation_code' => 'cancel_override', 'grace_period_minutes' => 0, 'retention_days' => 0, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => false],
            ['policy_code' => 'MASTER_SOFT_DELETE', 'module_code' => 'master_data', 'entity_name' => '*', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'CMS_SOFT_DELETE', 'module_code' => 'cms', 'entity_name' => '*', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => false, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
            ['policy_code' => 'QUOTATION_DRAFT_SOFT_DELETE', 'module_code' => 'sales', 'entity_name' => 'quotations', 'operation_code' => 'soft_delete', 'grace_period_minutes' => 0, 'retention_days' => 10, 'requires_reason' => true, 'requires_unposted' => true, 'requires_no_dependencies' => true, 'permanent_delete_allowed' => true],
        ];

        foreach ($policies as $policy) {
            DB::table('operation_policies')->updateOrInsert(
                ['policy_code' => $policy['policy_code']],
                [...$policy, 'is_active' => true, 'updated_at' => $now]
            );
        }
    }
}
