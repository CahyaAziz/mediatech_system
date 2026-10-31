<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('sales_order_id');
            $table->string('so_number', 50);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('salesperson_user_id')->nullable();
            $table->string('reference_number', 120);
            $table->string('sales_name_snapshot', 191);
            $table->string('sold_to_name_snapshot', 191);
            $table->text('sold_to_address_snapshot');
            $table->string('ship_to_name_snapshot', 191);
            $table->text('ship_to_address_snapshot')->nullable();
            $table->string('contact_phone_snapshot', 191);
            $table->string('attention_snapshot', 191);
            $table->string('payment_terms_snapshot', 255);
            $table->date('order_date');
            $table->date('delivery_date');
            $table->string('shipment_method', 191);
            $table->text('peripheral_notes');
            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_rate', 7, 4);
            $table->decimal('tax_amount', 18, 2);
            $table->decimal('other_charges', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2);
            $table->decimal('grand_total', 18, 2);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('status', 50);
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->unique('so_number');
            $table->unique('quotation_id');
            $table->index('customer_id');
            $table->index('created_by');
            $table->index('salesperson_user_id');
            $table->index('status');
            $table->index('order_date');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->restrictOnDelete();
            $table->foreign('quotation_id')->references('quotation_id')->on('quotations')->restrictOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('salesperson_user_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('cancelled_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};