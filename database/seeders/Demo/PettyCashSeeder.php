<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PettyCashSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $financeId = $this->userId('ica@meditech.co.id');
        $approverId = $this->userId('cahya@meditech.co.id');
        $rows = [
            ['PC-2026-001','2026-10-20 09:00:00','Pengeluaran','Operasional kantor','Pembelian alat tulis',450000,'nota-atk.svg','2026-10-20 09:30:00','BNK-001'],
            ['PC-2026-002','2026-10-21 09:00:00','Pemasukan','Pengembalian biaya','Pengembalian uang muka operasional',250000,'bukti-pengembalian.svg','2026-10-21 09:30:00','BNK-001'],
            ['PC-2026-003','2026-10-17 11:00:00','Pengeluaran','Operasional','Pembelian perlengkapan kantor',2350000,'bukti-operasional.svg','2026-10-17 12:00:00','BNK-001'],
            ['PC-2026-004','2026-10-14 14:00:00','Pengeluaran','Logistik','Biaya pengiriman barang ke customer',1250000,'bukti-logistik.svg','2026-10-14 15:00:00','BNK-001'],
        ];

        foreach ($rows as [$number, $date, $type, $category, $description, $amount, $proof, $postedAt, $bankCode]) {
            DB::table('petty_cash_transactions')->updateOrInsert(
                ['transaction_number' => $number],
                [
                    'transaction_date' => $date,
                    'transaction_type' => $type,
                    'category' => $category,
                    'description' => $description,
                    'amount' => $this->money($amount),
                    'payment_method' => 'Transfer bank',
                    'bank_account_id' => $this->bankAccountId($bankCode),
                    'proof_file_path' => 'demo/payment-proofs/' . $proof,
                    'recorded_by' => $financeId,
                    'approved_by' => $approverId,
                    'approved_at' => $postedAt,
                    'status' => 'Posted',
                    'created_at' => $date,
                    'updated_at' => $postedAt,
                    'posted_at' => $postedAt,
                    'deleted_at' => null,
                    'deleted_by' => null,
                    'deletion_reason' => null,
                ]
            );
            $pettyCashId = (int) DB::table('petty_cash_transactions')->where('transaction_number', $number)->value('petty_cash_id');
            $cashType = $type === 'Pemasukan' ? 'Cash In' : 'Cash Out';

            DB::table('cash_transactions')->updateOrInsert(
                ['transaction_number' => 'CSH-' . str_replace('PC-', '', $number)],
                [
                    'transaction_date' => $postedAt,
                    'transaction_type' => $cashType,
                    'source_type' => 'PETTY_CASH',
                    'category' => $category,
                    'description' => $description,
                    'amount' => $this->money($amount),
                    'bank_account_id' => $this->bankAccountId($bankCode),
                    'payment_id' => null,
                    'petty_cash_id' => $pettyCashId,
                    'recorded_by' => $approverId,
                    'status' => 'Posted',
                ]
            );
        }
    }
}