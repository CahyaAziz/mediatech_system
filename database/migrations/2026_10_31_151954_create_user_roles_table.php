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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->dateTime('assigned_at');
            $table->primary(['user_id', 'role_id']);
            $table->index('role_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id')->references('role_id')->on('roles')->cascadeOnDelete();
            $table->foreign('assigned_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};