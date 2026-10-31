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
        Schema::create('product_serials', function (Blueprint $table) {
            $table->bigIncrements('serial_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('serial_number', 120);
            $table->string('status', 50);
            $table->unique('serial_number');
            $table->index('product_id');
            $table->index('warehouse_id');
            $table->index('status');
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete();
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_serials');
    }
};