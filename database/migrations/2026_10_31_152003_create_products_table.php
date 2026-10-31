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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('sku', 80);
            $table->string('product_name', 191);
            $table->unsignedBigInteger('category_id');
            $table->string('product_type', 50);
            $table->string('unit', 30);
            $table->decimal('selling_price', 18, 2);
            $table->decimal('purchase_price', 18, 2);
            $table->decimal('min_stock', 18, 3);
            $table->boolean('track_serial')->default(false);
            $table->boolean('is_active')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('sku');
            $table->index('product_name');
            $table->index('category_id');
            $table->index('is_active');
            $table->foreign('category_id')->references('category_id')->on('product_categories')->restrictOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};