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
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->bigIncrements('receipt_item_id');
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity_received', 18, 3);
            $table->decimal('unit_cost', 18, 2);
            $table->string('unit', 30);
            $table->index('receipt_id');
            $table->index('product_id');
            $table->foreign('receipt_id')->references('receipt_id')->on('goods_receipts')->restrictOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};