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
        Schema::create('goods_issue_items', function (Blueprint $table) {
            $table->bigIncrements('goods_issue_item_id');
            $table->unsignedBigInteger('goods_issue_id');
            $table->unsignedBigInteger('so_item_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->decimal('quantity_issued', 18, 3);
            $table->string('unit', 30);
            $table->text('notes')->nullable();
            $table->index('goods_issue_id');
            $table->index('so_item_id');
            $table->index('warehouse_id');
            $table->foreign('goods_issue_id')->references('goods_issue_id')->on('goods_issues')->restrictOnDelete();
            $table->foreign('so_item_id')->references('so_item_id')->on('sales_order_items')->restrictOnDelete();
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_issue_items');
    }
};