<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionRuleSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $creatorId = $this->userId('mirachel.cindejona@meditech.co.id');
        $rules = [
            ['Penjualan di bawah Rp10 juta', 'sales', 0, 9999999.99, 'percentage', 1, 10],
            ['Penjualan minimal Rp10 juta', 'sales', 10000000, null, 'percentage', 2, 20],
            ['Profit kotor minimal Rp10 juta', 'gross_profit', 10000000, null, 'percentage', 5, 30],
        ];

        foreach ($rules as [$name, $basis, $min, $max, $calculation, $value, $priority]) {
            DB::table('commission_rules')->updateOrInsert(
                ['rule_name' => $name],
                [
                    'basis_type' => $basis,
                    'minimum_basis_amount' => $this->money($min),
                    'maximum_basis_amount' => $max === null ? null : $this->money($max),
                    'calculation_type' => $calculation,
                    'commission_value' => number_format((float) $value, 4, '.', ''),
                    'effective_from' => '2026-01-01',
                    'effective_until' => '2099-12-31',
                    'priority' => $priority,
                    'is_default' => true,
                    'is_active' => true,
                    'created_by' => $creatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
