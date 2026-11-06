<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $accounts = [
            ['account_code' => 'BNK-001', 'bank_name' => 'BCA', 'account_holder' => 'PT Meditech Cipta Inovasi', 'account_number' => '8120552381', 'purpose' => 'Operasional'],
            ['account_code' => 'BNK-002', 'bank_name' => 'Mandiri', 'account_holder' => 'PT Meditech Cipta Inovasi', 'account_number' => '12300099042', 'purpose' => 'Penerimaan'],
        ];

        foreach ($accounts as $account) {
            DB::table('bank_accounts')->updateOrInsert(
                ['account_code' => $account['account_code']],
                [...$account, 'is_active' => true]
            );
        }
    }
}
