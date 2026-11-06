<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $customers = [
            ['customer_code' => 'CUS-0007', 'name' => 'SMK Teknologi Nusantara', 'industry_sector' => 'Pendidikan', 'address' => 'Jl. Raya Serpong No. 12, Tangerang Selatan', 'phone' => '021-7560-1100', 'email' => 'sarpras@smktn.sch.id'],
            ['customer_code' => 'CUS-0006', 'name' => 'RS Harapan Sehat', 'industry_sector' => 'Rumah sakit', 'address' => 'Jl. Jend. Sudirman No. 21, Tangerang', 'phone' => '021-555-0182', 'email' => 'procurement@harapansehat.id'],
            ['customer_code' => 'CUS-0005', 'name' => 'PT Maju Retail Indonesia', 'industry_sector' => 'Retail', 'address' => 'Jl. BSD Raya No. 8, Tangerang Selatan', 'phone' => '021-5390-2200', 'email' => 'purchasing@majuretail.id'],
            ['customer_code' => 'CUS-0004', 'name' => 'PT Mitra Manufaktur Jaya', 'industry_sector' => 'Manufaktur', 'address' => 'Kawasan Industri Jatake Blok C-5, Tangerang', 'phone' => '021-5902-3300', 'email' => 'it@mitramanufaktur.id'],
            ['customer_code' => 'CUS-0003', 'name' => 'PT Cipta Distribusi Nusantara', 'industry_sector' => 'Distribusi', 'address' => 'Jl. Daan Mogot Km 18, Jakarta Barat', 'phone' => '021-5441-7700', 'email' => 'procurement@ciptadistribusi.id'],
            ['customer_code' => 'CUS-0002', 'name' => 'PT Energi Samudra Migas', 'industry_sector' => 'Oil & Gas', 'address' => 'Menara Energi Lt. 12, Jl. HR Rasuna Said, Jakarta Selatan', 'phone' => '021-5290-8800', 'email' => 'pengadaan@energisamudra.id'],
            ['customer_code' => 'CUS-0001', 'name' => 'PT Bank Sentosa Prima', 'industry_sector' => 'Perbankan', 'address' => 'Jl. MH Thamrin No. 40, Jakarta Pusat', 'phone' => '021-3190-5500', 'email' => 'logistik@banksentosa.id'],
        ];

        foreach ($customers as $customer) {
            DB::table('customers')->updateOrInsert(
                ['customer_code' => $customer['customer_code']],
                [...$customer, 'is_active' => true]
            );
        }
    }
}
