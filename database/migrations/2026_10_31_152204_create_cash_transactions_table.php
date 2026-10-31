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
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->bigIncrements('cash_transaction_id');
            $table->string('transaction_number', 60);
            $table->dateTime('transaction_date');
            $table->string('transaction_type', 50);
            $table->string('source_type', 50);
            $table->string('category', 191);
            $table->text('description')->nullable();
            $table->decimal('amount', 18, 2);
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('petty_cash_id')->nullable();
            $table->unsignedBigInteger('recorded_by');
            $table->string('status', 50);
            $table->unique('transaction_number');
            $table->unique('payment_id');
            $table->unique('petty_cash_id');
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('source_type');
            $table->index('bank_account_id');
            $table->index('recorded_by');
            $table->foreign('bank_account_id')->references('bank_account_id')->on('bank_accounts')->nullOnDelete();
            $table->foreign('payment_id')->references('payment_id')->on('payments')->nullOnDelete();
            $table->foreign('petty_cash_id')->references('petty_cash_id')->on('petty_cash_transactions')->nullOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};