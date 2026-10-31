<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL 8.0.16+ enforces CHECK constraints. Keep business-level checks
        // in Services too; database checks are a final integrity guard.
        DB::statement("ALTER TABLE quotation_items ADD CONSTRAINT chk_quotation_items_quantity CHECK (quantity > 0)");
        DB::statement("ALTER TABLE sales_order_items ADD CONSTRAINT chk_sales_order_items_quantity CHECK (quantity_ordered > 0 AND quantity_delivered >= 0 AND quantity_delivered <= quantity_ordered)");
        DB::statement("ALTER TABLE goods_receipt_items ADD CONSTRAINT chk_goods_receipt_items_quantity CHECK (quantity_received > 0)");
        DB::statement("ALTER TABLE goods_issue_items ADD CONSTRAINT chk_goods_issue_items_quantity CHECK (quantity_issued > 0)");
        DB::statement("ALTER TABLE invoice_items ADD CONSTRAINT chk_invoice_items_quantity CHECK (quantity > 0)");
        DB::statement("ALTER TABLE payment_allocations ADD CONSTRAINT chk_payment_allocations_amount CHECK (allocated_amount > 0)");
        DB::statement("ALTER TABLE payments ADD CONSTRAINT chk_payments_amount CHECK (amount > 0)");
        DB::statement("ALTER TABLE petty_cash_transactions ADD CONSTRAINT chk_petty_cash_amount CHECK (amount > 0)");
        DB::statement("ALTER TABLE commission_rules ADD CONSTRAINT chk_commission_rule_range CHECK (maximum_basis_amount IS NULL OR maximum_basis_amount >= minimum_basis_amount)");
        DB::statement("ALTER TABLE commission_rules ADD CONSTRAINT chk_commission_rule_value CHECK (commission_value >= 0)");
        DB::statement("ALTER TABLE stock_movements ADD CONSTRAINT chk_stock_movement_quantity CHECK (quantity > 0)");
        DB::statement("ALTER TABLE cash_transactions ADD CONSTRAINT chk_cash_transaction_single_source CHECK ((payment_id IS NOT NULL AND petty_cash_id IS NULL AND source_type = 'PAYMENT') OR (payment_id IS NULL AND petty_cash_id IS NOT NULL AND source_type = 'PETTY_CASH'))");
        DB::statement("ALTER TABLE quotations ADD CONSTRAINT chk_quotations_totals CHECK (subtotal >= 0 AND discount_amount >= 0 AND discount_amount <= subtotal AND tax_rate >= 0 AND tax_rate <= 100 AND tax_amount >= 0 AND other_charges >= 0 AND grand_total >= 0)");
        DB::statement("ALTER TABLE sales_orders ADD CONSTRAINT chk_sales_orders_totals CHECK (subtotal >= 0 AND discount_amount >= 0 AND discount_amount <= subtotal AND tax_rate >= 0 AND tax_rate <= 100 AND tax_amount >= 0 AND other_charges >= 0 AND grand_total >= 0)");
        DB::statement("ALTER TABLE cash_transactions ADD CONSTRAINT chk_cash_transactions_amount CHECK (amount > 0)");
        DB::statement("ALTER TABLE invoices ADD CONSTRAINT chk_invoice_totals CHECK (subtotal >= 0 AND discount_amount >= 0 AND tax_amount >= 0 AND other_charges >= 0 AND grand_total >= 0)");
        DB::statement("ALTER TABLE stock_movements ADD CONSTRAINT chk_stock_movement_source CHECK ((movement_type = 'GOODS_IN' AND receipt_item_id IS NOT NULL AND goods_issue_item_id IS NULL AND reversal_of_movement_id IS NULL) OR (movement_type = 'GOODS_OUT' AND goods_issue_item_id IS NOT NULL AND receipt_item_id IS NULL AND reversal_of_movement_id IS NULL) OR (movement_type IN ('ADJUSTMENT_IN','ADJUSTMENT_OUT','OPENING_BALANCE') AND receipt_item_id IS NULL AND goods_issue_item_id IS NULL AND reversal_of_movement_id IS NULL AND adjustment_reason IS NOT NULL) OR (movement_type IN ('REVERSAL_IN','REVERSAL_OUT') AND reversal_of_movement_id IS NOT NULL AND receipt_item_id IS NULL AND goods_issue_item_id IS NULL))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE stock_movements DROP CHECK chk_stock_movement_source");
        DB::statement("ALTER TABLE cash_transactions DROP CHECK chk_cash_transactions_amount");
        DB::statement("ALTER TABLE sales_orders DROP CHECK chk_sales_orders_totals");
        DB::statement("ALTER TABLE quotations DROP CHECK chk_quotations_totals");
        DB::statement("ALTER TABLE invoices DROP CHECK chk_invoice_totals");
        DB::statement("ALTER TABLE cash_transactions DROP CHECK chk_cash_transaction_single_source");
        DB::statement("ALTER TABLE stock_movements DROP CHECK chk_stock_movement_quantity");
        DB::statement("ALTER TABLE commission_rules DROP CHECK chk_commission_rule_value");
        DB::statement("ALTER TABLE commission_rules DROP CHECK chk_commission_rule_range");
        DB::statement("ALTER TABLE petty_cash_transactions DROP CHECK chk_petty_cash_amount");
        DB::statement("ALTER TABLE payments DROP CHECK chk_payments_amount");
        DB::statement("ALTER TABLE payment_allocations DROP CHECK chk_payment_allocations_amount");
        DB::statement("ALTER TABLE invoice_items DROP CHECK chk_invoice_items_quantity");
        DB::statement("ALTER TABLE goods_issue_items DROP CHECK chk_goods_issue_items_quantity");
        DB::statement("ALTER TABLE goods_receipt_items DROP CHECK chk_goods_receipt_items_quantity");
        DB::statement("ALTER TABLE sales_order_items DROP CHECK chk_sales_order_items_quantity");
        DB::statement("ALTER TABLE quotation_items DROP CHECK chk_quotation_items_quantity");
    }
};