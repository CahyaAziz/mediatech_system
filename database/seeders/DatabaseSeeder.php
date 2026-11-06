<?php

namespace Database\Seeders;

use Database\Seeders\System\RoleSeeder;
use Database\Seeders\System\PermissionSeeder;
use Database\Seeders\System\RolePermissionSeeder;
use Database\Seeders\System\OperationPolicySeeder;
use Database\Seeders\System\SuperAdminSeeder;
use Database\Seeders\Master\BankAccountSeeder;
use Database\Seeders\Master\CompanyDocumentSettingSeeder;
use Database\Seeders\Master\CustomerSeeder;
use Database\Seeders\Master\ProductCategorySeeder;
use Database\Seeders\Master\ProductSeeder;
use Database\Seeders\Master\SupplierSeeder;
use Database\Seeders\Master\WarehouseSeeder;
use Database\Seeders\Demo\AuditLogSeeder;
use Database\Seeders\Demo\CmsContentSeeder;
use Database\Seeders\Demo\CommissionRuleSeeder;
use Database\Seeders\Demo\CommissionSimulationSeeder;
use Database\Seeders\Demo\DemoUserSeeder;
use Database\Seeders\Demo\GoodsOutSeeder;
use Database\Seeders\Demo\GoodsReceiptSeeder;
use Database\Seeders\Demo\InvoiceSeeder;
use Database\Seeders\Demo\PaymentSeeder;
use Database\Seeders\Demo\PettyCashSeeder;
use Database\Seeders\Demo\QuotationSeeder;
use Database\Seeders\Demo\SalesOrderSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder sistem boleh dijalankan pada environment apa pun.
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            OperationPolicySeeder::class,
            SuperAdminSeeder::class,
        ]);

        // Data customer, supplier, produk, transaksi, dan user demo hanya untuk local/testing.
        if (!app()->environment(['local', 'testing'])) {
            $this->command?->info('Seeder data demo dilewati karena environment bukan local/testing.');
            return;
        }

        $this->call([
            DemoUserSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            ProductCategorySeeder::class,
            WarehouseSeeder::class,
            BankAccountSeeder::class,
            ProductSeeder::class,
            CompanyDocumentSettingSeeder::class,
            QuotationSeeder::class,
            SalesOrderSeeder::class,
            GoodsReceiptSeeder::class,
            GoodsOutSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            PettyCashSeeder::class,
            CommissionRuleSeeder::class,
            CommissionSimulationSeeder::class,
            CmsContentSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
