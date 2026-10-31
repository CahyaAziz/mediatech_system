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
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('permission_id');
            $table->string('permission_code', 120);
            $table->string('module_code', 80);
            $table->string('operation_code', 80);
            $table->string('description', 191);
            $table->boolean('is_assignable')->default(false);
            $table->boolean('is_destructive_operation')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unique('permission_code');
            $table->index('module_code');
            $table->index('operation_code');
            $table->index('is_active');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};