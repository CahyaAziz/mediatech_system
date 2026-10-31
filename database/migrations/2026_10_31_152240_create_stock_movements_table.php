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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('movement_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('receipt_item_id')->nullable();
            $table->unsignedBigInteger('goods_issue_item_id')->nullable();
            $table->unsignedBigInteger('serial_id')->nullable();
            $table->unsignedBigInteger('reversal_of_movement_id')->nullable();
            $table->unsignedBigInteger('recorded_by');
            $table->dateTime('movement_date');
            $table->string('movement_type', 50);
            $table->decimal('quantity', 18, 3);
            $table->string('reference_number', 120);
            $table->text('adjustment_reason')->nullable();
            $table->index('product_id');
            $table->index('warehouse_id');
            $table->index('receipt_item_id');
            $table->index('goods_issue_item_id');
            $table->index('serial_id');
            $table->index('movement_date');
            $table->index('movement_type');
            $table->index('recorded_by');
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete();
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete();
            $table->foreign('receipt_item_id')->references('receipt_item_id')->on('goods_receipt_items')->nullOnDelete();
            $table->foreign('goods_issue_item_id')->references('goods_issue_item_id')->on('goods_issue_items')->nullOnDelete();
            $table->foreign('serial_id')->references('serial_id')->on('product_serials')->nullOnDelete();
            $table->foreign('reversal_of_movement_id')->references('movement_id')->on('stock_movements')->nullOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};