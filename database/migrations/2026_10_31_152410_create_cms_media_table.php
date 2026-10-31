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
        Schema::create('cms_media', function (Blueprint $table) {
            $table->bigIncrements('media_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('media_type', 100);
            $table->string('alt_text', 255);
            $table->integer('file_size_kb');
            $table->integer('width_px');
            $table->integer('height_px');
            $table->unsignedBigInteger('uploaded_by');
            $table->dateTime('uploaded_at');
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->index('uploaded_by');
            $table->index('media_type');
            $table->foreign('uploaded_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_media');
    }
};