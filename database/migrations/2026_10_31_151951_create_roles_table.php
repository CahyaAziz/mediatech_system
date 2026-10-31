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
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('role_name', 100);
            $table->string('description', 191);
            $table->boolean('is_system_role')->default(false);
            $table->boolean('is_super_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('role_name');
            $table->index('is_active');
            $table->foreign('created_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};