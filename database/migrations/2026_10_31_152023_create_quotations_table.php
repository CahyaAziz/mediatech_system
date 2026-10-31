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
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('quotation_id');
            $table->string('quotation_number', 50);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('salesperson_user_id')->nullable();
            $table->unsignedBigInteger('created_from_quotation_id')->nullable();
            $table->date('quotation_date');
            $table->date('valid_until');
            $table->string('bill_to_name_snapshot', 191);
            $table->text('bill_to_address_snapshot')->nullable();
            $table->string('contact_phone_snapshot', 191);
            $table->string('attention_snapshot', 191);
            $table->string('payment_terms_snapshot', 255);
            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_rate', 7, 4);
            $table->decimal('tax_amount', 18, 2);
            $table->decimal('other_charges', 18, 2)->default(0);
            $table->decimal('grand_total', 18, 2);
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('status', 50);
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->unique('quotation_number');
            $table->index('customer_id');
            $table->index('salesperson_user_id');
            $table->index('status');
            $table->index('created_from_quotation_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->restrictOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('salesperson_user_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('created_from_quotation_id')->references('quotation_id')->on('quotations')->nullOnDelete();
            $table->foreign('deleted_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};