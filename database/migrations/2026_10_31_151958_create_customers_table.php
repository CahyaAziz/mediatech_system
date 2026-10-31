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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('customer_id');
            $table->string('customer_code', 50);
            $table->string('name', 180);
            $table->string('industry_sector', 100);
            $table->string('address', 191);
            $table->string('phone', 191);
            $table->string('email', 255);
            $table->boolean('is_active')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('customer_code');
            $table->index('name');
            $table->index('is_active');
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};