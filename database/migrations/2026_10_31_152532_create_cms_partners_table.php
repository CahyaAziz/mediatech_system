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
        Schema::create('cms_partners', function (Blueprint $table) {
            $table->bigIncrements('partner_id');
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('partner_name', 191);
            $table->string('website_url', 500);
            $table->integer('display_order');
            $table->boolean('is_active')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('media_id');
            $table->unique('partner_name');
            $table->index('category_id');
            $table->index('display_order');
            $table->index('is_active');
            $table->foreign('media_id')->references('media_id')->on('cms_media')->restrictOnDelete();
            $table->foreign('category_id')->references('category_id')->on('product_categories')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_partners');
    }
};