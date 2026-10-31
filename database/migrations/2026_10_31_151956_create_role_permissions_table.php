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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('granted_by')->nullable();
            $table->dateTime('granted_at');
            $table->primary(['role_id', 'permission_id']);
            $table->index('permission_id');
            $table->foreign('role_id')->references('role_id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->cascadeOnDelete();
            $table->foreign('granted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};