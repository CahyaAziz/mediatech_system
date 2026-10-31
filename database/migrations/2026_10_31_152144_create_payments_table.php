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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->string('payment_number', 50);
            $table->date('payment_date');
            $table->decimal('amount', 18, 2);
            $table->string('payment_method', 50);
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->string('reference_number', 120);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->string('status', 50);
            $table->unique('payment_number');
            $table->index('payment_date');
            $table->index('status');
            $table->index('recorded_by');
            $table->index('validated_by');
            $table->foreign('bank_account_id')->references('bank_account_id')->on('bank_accounts')->nullOnDelete();
            $table->foreign('recorded_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('validated_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};