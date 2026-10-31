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
        Schema::create('commission_payouts', function (Blueprint $table) {
            $table->bigIncrements('commission_payout_id');
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('salesperson_user_id');
            $table->string('basis_type_snapshot', 50);
            $table->decimal('basis_amount', 18, 2);
            $table->string('calculation_type_snapshot', 50);
            $table->decimal('commission_value_snapshot', 18, 4);
            $table->decimal('commission_amount', 18, 2);
            $table->string('status', 50);
            $table->dateTime('calculated_at');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->index('sales_order_id');
            $table->index('salesperson_user_id');
            $table->index('status');
            $table->index('calculated_at');
            $table->unique(['sales_order_id', 'salesperson_user_id']);
            $table->foreign('rule_id')->references('rule_id')->on('commission_rules')->restrictOnDelete();
            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders')->restrictOnDelete();
            $table->foreign('salesperson_user_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('approved_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payouts');
    }
};