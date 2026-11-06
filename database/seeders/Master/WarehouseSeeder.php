<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        foreach ([
            ['warehouse_code' => 'WH-001', 'warehouse_name' => 'Gudang Utama', 'location' => 'Tangerang'],
            ['warehouse_code' => 'WH-002', 'warehouse_name' => 'Gudang Jakarta', 'location' => 'Jakarta Barat'],
        ] as $warehouse) {
            DB::table('warehouses')->updateOrInsert(
                ['warehouse_code' => $warehouse['warehouse_code']],
                [...$warehouse, 'is_active' => true]
            );
        }
    }
}
