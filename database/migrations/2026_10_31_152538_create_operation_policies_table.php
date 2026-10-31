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
        Schema::create('operation_policies', function (Blueprint $table) {
            $table->bigIncrements('policy_id');
            $table->string('policy_code', 120);
            $table->string('module_code', 80);
            $table->string('entity_name', 100);
            $table->string('operation_code', 80);
            $table->integer('grace_period_minutes');
            $table->integer('retention_days');
            $table->boolean('requires_reason')->default(false);
            $table->boolean('requires_unposted')->default(false);
            $table->boolean('requires_no_dependencies')->default(false);
            $table->boolean('permanent_delete_allowed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unique('policy_code');
            $table->index('module_code');
            $table->index('entity_name');
            $table->index('operation_code');
            $table->index('is_active');
            $table->foreign('updated_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_policies');
    }
};