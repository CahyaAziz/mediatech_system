<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyDocumentSettingSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $userId = DB::table('users')->where('email', 'mirachel@meditech.co.id')->value('user_id');
        foreach (['quotation', 'sales_order', 'invoice'] as $type) {
            DB::table('company_document_settings')->updateOrInsert(
                ['document_type' => $type],
                [
                    'company_name' => 'PT Meditech Cipta Inovasi',
                    'company_address' => 'Alamat perusahaan perlu disesuaikan dengan data resmi perusahaan.',
                    'company_phone' => '021-0000-0000',
                    'company_email' => 'info@meditech.co.id',
                    'company_tax_number' => 'NPWP belum diatur',
                    'logo_path' => 'images/meditech-logo-placeholder.svg',
                    'quotation_title' => 'PENAWARAN',
                    'sales_order_title' => 'SALES ORDER',
                    'invoice_title' => 'INVOICE',
                    'default_tax_label' => 'PPN (11%)',
                    'default_tax_rate' => 11.0000,
                    'default_payment_terms' => 'Net 30',
                    'footer_notes' => 'Terima kasih atas kepercayaan Anda.',
                    'signature_label' => 'Bagian Penjualan',
                    'is_active' => true,
                    'updated_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}