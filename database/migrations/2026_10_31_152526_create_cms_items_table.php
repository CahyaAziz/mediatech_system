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
        Schema::create('cms_items', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('media_id')->nullable();
            $table->string('section_key', 191);
            $table->string('title', 191);
            $table->string('subtitle', 191);
            $table->string('item_value', 191);
            $table->string('meta', 191);
            $table->text('description')->nullable();
            $table->integer('sort_order');
            $table->boolean('is_active')->default(true);
            $table->string('media_aspect_ratio', 30);
            $table->string('media_fit_mode', 30);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->index('page_id');
            $table->index('media_id');
            $table->index('section_key');
            $table->index('sort_order');
            $table->foreign('page_id')->references('page_id')->on('cms_pages')->restrictOnDelete();
            $table->foreign('media_id')->references('media_id')->on('cms_media')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_items');
    }
};