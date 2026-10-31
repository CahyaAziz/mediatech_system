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
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->bigIncrements('allocation_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('allocated_amount', 18, 2);
            $table->index('payment_id');
            $table->index('invoice_id');
            $table->unique(['payment_id', 'invoice_id']);
            $table->foreign('payment_id')->references('payment_id')->on('payments')->restrictOnDelete();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};