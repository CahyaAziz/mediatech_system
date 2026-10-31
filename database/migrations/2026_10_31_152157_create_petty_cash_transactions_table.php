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
        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->bigIncrements('petty_cash_id');
            $table->string('transaction_number', 60);
            $table->dateTime('transaction_date');
            $table->string('transaction_type', 50);
            $table->string('category', 191);
            $table->text('description')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('payment_method', 50);
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->string('proof_file_path', 191);
            $table->unsignedBigInteger('recorded_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('status', 50);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('posted_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('transaction_number');
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('status');
            $table->index('recorded_by');
            $table->index('posted_at');
            $table->foreign('bank_account_id')->references('bank_account_id')->on('bank_accounts')->nullOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('approved_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_transactions');
    }
};