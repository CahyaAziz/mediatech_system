<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $suppliers = [
            ['supplier_code' => 'SUP-0003', 'name' => 'PT Distribusi Komputer Nusantara', 'phone' => '021-5560-3400', 'address' => 'Jakarta Barat'],
            ['supplier_code' => 'SUP-0002', 'name' => 'CV Elektronik Sentra Jaya', 'phone' => '022-700-2200', 'address' => 'Bandung'],
            ['supplier_code' => 'SUP-0001', 'name' => 'PT Prima Digital Solusi', 'phone' => '021-3840-1800', 'address' => 'Jakarta Pusat'],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->updateOrInsert(
                ['supplier_code' => $supplier['supplier_code']],
                [...$supplier, 'is_active' => true]
            );
        }
    }
}
