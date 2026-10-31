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
        Schema::create('payment_attachments', function (Blueprint $table) {
            $table->bigIncrements('attachment_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('media_type', 100);
            $table->integer('file_size_kb');
            $table->unsignedBigInteger('uploaded_by');
            $table->dateTime('uploaded_at');
            $table->index('payment_id');
            $table->index('uploaded_by');
            $table->foreign('payment_id')->references('payment_id')->on('payments')->restrictOnDelete();
            $table->foreign('uploaded_by')->references('user_id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_attachments');
    }
};