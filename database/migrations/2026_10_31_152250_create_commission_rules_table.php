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
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->bigIncrements('rule_id');
            $table->string('rule_name', 191);
            $table->string('basis_type', 50);
            $table->decimal('minimum_basis_amount', 18, 2);
            $table->decimal('maximum_basis_amount', 18, 2)->nullable();
            $table->string('calculation_type', 50);
            $table->decimal('commission_value', 18, 4);
            $table->date('effective_from');
            $table->date('effective_until');
            $table->integer('priority');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->index('basis_type');
            $table->index('is_active');
            $table->index('effective_from');
            $table->index('effective_until');
            $table->index('priority');
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};