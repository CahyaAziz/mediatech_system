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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('invoice_id');
            $table->string('invoice_number', 50);
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('created_by');
            $table->string('reference_number', 120);
            $table->string('sales_name_snapshot', 191);
            $table->string('bill_to_snapshot', 255);
            $table->text('bill_to_address_snapshot')->nullable();
            $table->string('ship_to_snapshot', 255);
            $table->text('ship_to_address_snapshot')->nullable();
            $table->string('contact_phone_snapshot', 191);
            $table->string('attention_snapshot', 191);
            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_rate', 7, 4);
            $table->decimal('tax_amount', 18, 2);
            $table->decimal('other_charges', 18, 2)->default(0);
            $table->decimal('grand_total', 18, 2);
            $table->string('payment_terms_snapshot', 255);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('invoice_type', 50);
            $table->string('status', 50);
            $table->decimal('total_amount', 18, 2);
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unique('invoice_number');
            $table->index('sales_order_id');
            $table->index('created_by');
            $table->index('status');
            $table->index('invoice_date');
            $table->index('due_date');
            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders')->restrictOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};