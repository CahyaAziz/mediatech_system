<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $recordedBy = $this->userId('ica@meditech.co.id');
        $validatedBy = $recordedBy; // Prototype menampilkan Finance sebagai pencatat sekaligus validator payment demo.
        $payments = [
            ['PAY-2026-041','INV-2026-024','2026-10-16','Transfer bank',30000000,'BNK-001','TRF-161026-041','Validated','2026-10-16 11:15:00','Pembayaran sebagian customer.'],
            ['PAY-2026-042','INV-2026-022','2026-10-18','Transfer bank',50000000,'BNK-002','TRF-181026-042','Pending Validation',null,'Menunggu validasi finance manager.'],
            ['PAY-2026-040','INV-2026-023','2026-10-15','Transfer bank',null,'BNK-001','TRF-151026-040','Validated','2026-10-15 15:20:00','Pembayaran lunas invoice.'],
            ['PAY-2026-043','INV-2026-021','2026-10-18','Virtual account',10000000,'BNK-001','VA-181026-043','Pending Validation',null,'Menunggu validasi pembayaran.'],
        ];

        foreach ($payments as [$number, $invoiceNumber, $date, $method, $amount, $bankCode, $reference, $status, $validatedAt, $notes]) {
            $invoiceId = $this->invoiceId($invoiceNumber);
            $invoice = DB::table('invoices')->where('invoice_id', $invoiceId)->first();
            $amount = $amount ?? (float) $invoice->grand_total;
            $values = [
                'payment_date' => $date,
                'amount' => $this->money($amount),
                'payment_method' => $method,
                'bank_account_id' => $this->bankAccountId($bankCode),
                'reference_number' => $reference,
                'notes' => $notes,
                'recorded_by' => $recordedBy,
                'validated_by' => $status === 'Validated' ? $validatedBy : null,
                'validated_at' => $validatedAt,
                'status' => $status,
            ];
            DB::table('payments')->updateOrInsert(['payment_number' => $number], $values);
            $paymentId = $this->paymentId($number);

            DB::table('payment_allocations')->updateOrInsert(
                ['payment_id' => $paymentId, 'invoice_id' => $invoiceId],
                ['allocated_amount' => $this->money($amount)]
            );

            // Attachment demo memakai file SVG placeholder di storage/app/private/demo/payment-proofs.
            DB::table('payment_attachments')->updateOrInsert(
                ['payment_id' => $paymentId, 'file_path' => 'demo/payment-proofs/sample-transfer-proof.svg'],
                [
                    'file_name' => strtolower($number) . '-bukti-transfer.svg',
                    'media_type' => 'image/svg+xml',
                    'file_size_kb' => 2,
                    'uploaded_by' => $recordedBy,
                    'uploaded_at' => $date . ' 10:00:00',
                ]
            );

            // Cash-in hanya dibuat setelah payment berstatus Validated.
            if ($status === 'Validated') {
                DB::table('cash_transactions')->updateOrInsert(
                    ['transaction_number' => 'CSH-' . substr($number, 4)],
                    [
                        'transaction_date' => $validatedAt,
                        'transaction_type' => 'Cash In',
                        'source_type' => 'PAYMENT',
                        'category' => 'Pembayaran customer',
                        'description' => 'Pembayaran ' . $invoiceNumber,
                        'amount' => $this->money($amount),
                        'bank_account_id' => $this->bankAccountId($bankCode),
                        'payment_id' => $paymentId,
                        'petty_cash_id' => null,
                        'recorded_by' => $validatedBy,
                        'status' => 'Posted',
                    ]
                );
            }
        }
    }
}