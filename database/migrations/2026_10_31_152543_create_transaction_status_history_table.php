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
        Schema::create('transaction_status_history', function (Blueprint $table) {
            $table->bigIncrements('status_history_id');
            $table->string('entity_name', 100);
            $table->string('entity_id', 191);
            $table->string('old_status', 50);
            $table->string('new_status', 50);
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->dateTime('changed_at');
            $table->text('reason')->nullable();
            $table->string('request_id', 100);
            $table->index('entity_name');
            $table->index('entity_id');
            $table->index('changed_by');
            $table->index('changed_at');
            $table->index(['entity_name', 'entity_id', 'changed_at'], 'status_history_entity_timeline_idx');
            $table->foreign('changed_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_status_history');
    }
};