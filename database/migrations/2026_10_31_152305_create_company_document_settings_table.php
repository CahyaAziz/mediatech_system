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
        Schema::create('company_document_settings', function (Blueprint $table) {
            $table->bigIncrements('setting_id');
            $table->string('document_type', 50);
            $table->string('company_name', 180);
            $table->text('company_address')->nullable();
            $table->string('company_phone', 50);
            $table->string('company_email', 255);
            $table->string('company_tax_number', 80);
            $table->string('logo_path', 500);
            $table->string('quotation_title', 120);
            $table->string('sales_order_title', 120);
            $table->string('invoice_title', 120);
            $table->string('default_tax_label', 80);
            $table->decimal('default_tax_rate', 7, 4);
            $table->text('default_payment_terms');
            $table->text('footer_notes')->nullable();
            $table->string('signature_label', 120);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->unique('document_type');
            $table->index('is_active');
            $table->foreign('updated_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_document_settings');
    }
};