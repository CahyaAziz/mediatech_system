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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('audit_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 100);
            $table->string('entity_name', 100);
            $table->string('entity_id', 191);
            $table->dateTime('created_at')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values');
            $table->json('new_values');
            $table->text('reason')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500);
            $table->string('request_id', 100);
            $table->index('user_id');
            $table->index('action');
            $table->index('entity_name');
            $table->index('entity_id');
            $table->index('created_at');
            $table->index('request_id');
            $table->index(['entity_name', 'entity_id', 'created_at'], 'audit_entity_timeline_idx');
            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};