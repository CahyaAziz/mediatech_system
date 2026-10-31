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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->bigIncrements('quotation_item_id');
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_code_snapshot', 191);
            $table->string('product_name_snapshot', 191);
            $table->string('unit_snapshot', 191);
            $table->decimal('quantity', 18, 3);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('unit_cost_snapshot', 18, 2);
            $table->decimal('discount', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2);
            $table->index('quotation_id');
            $table->index('product_id');
            $table->foreign('quotation_id')->references('quotation_id')->on('quotations')->restrictOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};