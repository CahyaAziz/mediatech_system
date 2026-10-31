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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('bank_account_id');
            $table->string('account_code', 50);
            $table->string('bank_name', 120);
            $table->string('account_holder', 180);
            $table->string('account_number', 100);
            $table->string('purpose', 100);
            $table->boolean('is_active')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('account_code');
            $table->unique('account_number');
            $table->index('is_active');
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};