<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuotationSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $salesId = $this->userId('rafli.ahmad@meditech.co.id');
        $rows = [
            ['QT-2026-018','CUS-0007','2026-10-18','2026-11-17','Pending Approval','', [['PRD-001',30,3],['PRD-017',30,0]]],
            ['QT-2026-017','CUS-0003','2026-10-17','2026-11-16','Approved','', [['PRD-011',16,0],['PRD-012',1,0],['PRD-015',16,0]]],
            ['QT-2026-016','CUS-0001','2026-10-15','2026-11-14','Rejected','Mohon tinjau kembali diskon dan masa berlaku garansi.', [['PRD-004',2,0],['PRD-017',2,0]]],
            ['QT-2026-015','CUS-0005','2026-10-14','2026-11-13','Pending Approval','', [['PRD-005',10,0],['PRD-006',6,0],['PRD-017',16,0]]],
            ['QT-2026-014','CUS-0004','2026-10-12','2026-11-11','Draft','', [['PRD-008',6,0],['PRD-017',6,0]]],
            ['QT-2026-013','CUS-0006','2026-10-10','2026-11-09','Pending Approval','', [['PRD-013',1,0]]],
            // Quotation historis ini menjadi basis SO dan invoice yang tampil di prototype.
            ['QT-2026-011','CUS-0002','2026-10-01','2026-10-31','Approved','', [['PRD-010',5,0],['PRD-017',16,0]]],
            ['QT-2026-010','CUS-0007','2026-09-28','2026-10-28','Approved','', [['PRD-013',1,0]]],
            ['QT-2026-009','CUS-0005','2026-09-26','2026-10-26','Approved','', [['PRD-005',10,0],['PRD-006',6,0],['PRD-017',16,0]]],
        ];

        foreach ($rows as [$number, $customerCode, $date, $validUntil, $status, $notes, $items]) {
            $customerId = $this->customerId($customerCode);
            $customer = DB::table('customers')->where('customer_id', $customerId)->first();
            $itemRows = [];
            $subtotal = 0;

            foreach ($items as [$sku, $quantity, $discountPercent]) {
                $product = DB::table('products')->where('sku', $sku)->first();
                $gross = (float) $product->selling_price * $quantity;
                $lineSubtotal = round($gross * (1 - ($discountPercent / 100)), 2);
                $subtotal += $lineSubtotal;
                $itemRows[] = [
                    'product_id' => $product->product_id,
                    'product_code_snapshot' => $product->sku,
                    'product_name_snapshot' => $product->product_name,
                    'unit_snapshot' => $product->unit,
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price,
                    'unit_cost_snapshot' => $product->purchase_price,
                    'discount' => $discountPercent,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $taxRate = 11.00;
            $taxAmount = round($subtotal * $taxRate / 100, 2);
            $grandTotal = $subtotal + $taxAmount;
            $quotationId = DB::table('quotations')->where('quotation_number', $number)->value('quotation_id');
            $values = [
                'customer_id' => $customerId,
                'created_by' => $salesId,
                'salesperson_user_id' => $salesId,
                'quotation_date' => $date,
                'valid_until' => $validUntil,
                'bill_to_name_snapshot' => $customer->name,
                'bill_to_address_snapshot' => $customer->address,
                'contact_phone_snapshot' => $customer->phone,
                'attention_snapshot' => 'Bagian Pengadaan',
                'payment_terms_snapshot' => 'Net 30',
                'subtotal' => $this->money($subtotal),
                'discount_amount' => '0.00',
                'tax_rate' => $taxRate,
                'tax_amount' => $this->money($taxAmount),
                'other_charges' => '0.00',
                'grand_total' => $this->money($grandTotal),
                'notes' => $notes ?: null,
                'status' => $status,
                'created_at' => $date . ' 09:00:00',
                'updated_at' => $date . ' 09:00:00',
            ];

            if ($quotationId) {
                DB::table('quotations')->where('quotation_id', $quotationId)->update($values);
            } else {
                $quotationId = DB::table('quotations')->insertGetId([
                    'quotation_number' => $number,
                    'customer_id' => $customerId,
                    'created_by' => $salesId,
                    'salesperson_user_id' => $salesId,
                    'created_from_quotation_id' => null,
                    ...$values,
                ], 'quotation_id');
            }

            foreach ($itemRows as $item) {
                DB::table('quotation_items')->updateOrInsert(
                    ['quotation_id' => $quotationId, 'product_id' => $item['product_id']],
                    $item
                );
            }
        }
    }
}
