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
        Schema::create('goods_issues', function (Blueprint $table) {
            $table->bigIncrements('goods_issue_id');
            $table->string('goods_issue_number', 50);
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('posted_at')->nullable();
            $table->string('status', 50);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('reversed_by')->nullable();
            $table->dateTime('reversed_at')->nullable();
            $table->text('reversal_reason')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('goods_issue_number');
            $table->index('sales_order_id');
            $table->index('status');
            $table->index('created_at');
            $table->foreign('sales_order_id')->references('sales_order_id')->on('sales_orders')->restrictOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('reversed_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_issues');
    }
};