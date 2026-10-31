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
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->bigIncrements('receipt_id');
            $table->string('receipt_number', 50);
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('received_by');
            $table->date('receipt_date');
            $table->string('status', 50);
            $table->dateTime('posted_at')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('receipt_number');
            $table->index('supplier_id');
            $table->index('warehouse_id');
            $table->index('received_by');
            $table->index('status');
            $table->index('receipt_date');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->restrictOnDelete();
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete();
            $table->foreign('received_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};