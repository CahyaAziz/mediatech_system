<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

trait SeederSupport
{
    protected function upsertRow(string $table, array $uniqueBy, array $values): void
    {
        DB::table($table)->updateOrInsert($uniqueBy, $values);
    }

    protected function idBy(string $table, string $column, mixed $value, string $idColumn): int
    {
        $id = DB::table($table)->where($column, $value)->value($idColumn);
        if (!$id) {
            throw new RuntimeException("Data {$table}.{$column}={$value} belum tersedia. Periksa urutan seeder.");
        }

        return (int) $id;
    }

    protected function userId(string $email): int
    {
        return $this->idBy('users', 'email', $email, 'user_id');
    }

    protected function roleId(string $name): int
    {
        return $this->idBy('roles', 'role_name', $name, 'role_id');
    }

    protected function customerId(string $code): int
    {
        return $this->idBy('customers', 'customer_code', $code, 'customer_id');
    }

    protected function supplierId(string $code): int
    {
        return $this->idBy('suppliers', 'supplier_code', $code, 'supplier_id');
    }

    protected function categoryId(string $code): int
    {
        return $this->idBy('product_categories', 'category_code', $code, 'category_id');
    }

    protected function productId(string $sku): int
    {
        return $this->idBy('products', 'sku', $sku, 'product_id');
    }

    protected function warehouseId(string $code): int
    {
        return $this->idBy('warehouses', 'warehouse_code', $code, 'warehouse_id');
    }

    protected function bankAccountId(string $code): int
    {
        return $this->idBy('bank_accounts', 'account_code', $code, 'bank_account_id');
    }

    protected function quotationId(string $number): int
    {
        return $this->idBy('quotations', 'quotation_number', $number, 'quotation_id');
    }

    protected function salesOrderId(string $number): int
    {
        return $this->idBy('sales_orders', 'so_number', $number, 'sales_order_id');
    }

    protected function invoiceId(string $number): int
    {
        return $this->idBy('invoices', 'invoice_number', $number, 'invoice_id');
    }

    protected function paymentId(string $number): int
    {
        return $this->idBy('payments', 'payment_number', $number, 'payment_id');
    }

    protected function isDemoEnvironment(): bool
    {
        if (!app()->environment(['local', 'testing'])) {
            $this->command?->warn(static::class . ' dilewati. Seeder data demo hanya boleh dijalankan di local/testing.');
            return false;
        }

        return true;
    }

    protected function money(float|int $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }
}
