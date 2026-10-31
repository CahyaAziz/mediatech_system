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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('invoice_item_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('so_item_id');
            $table->string('product_code_snapshot', 191);
            $table->string('product_name_snapshot', 191);
            $table->string('unit_snapshot', 191);
            $table->decimal('quantity', 18, 3);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2);
            $table->index('invoice_id');
            $table->index('so_item_id');
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->restrictOnDelete();
            $table->foreign('so_item_id')->references('so_item_id')->on('sales_order_items')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};