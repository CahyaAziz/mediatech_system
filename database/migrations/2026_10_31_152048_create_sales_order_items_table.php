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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->bigIncrements('so_item_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_code_snapshot', 191);
            $table->string('product_name_snapshot', 191);
            $table->string('unit_snapshot', 191);
            $table->decimal('quantity_ordered', 18, 3);
            $table->decimal('quantity_delivered', 18, 3)->default(0);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('unit_cost_snapshot', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2);
            $table->index('sales_order_id');
            $table->index('product_id');
            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders')->restrictOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};