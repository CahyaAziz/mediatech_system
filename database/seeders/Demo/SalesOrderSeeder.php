<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesOrderSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $salesId = $this->userId('rafli.ahmad@meditech.co.id');
        $orders = [
            ['SO-2026-031','QT-2026-017','2026-10-17','2026-11-03','Processing','284/GA/IX/2026.TRIM','KURIR','Pengiriman dilakukan bertahap.'],
            ['SO-2026-030','QT-2026-011','2026-10-12','2026-10-18','Completed','283/GA/IX/2026.TRIM','KURIR','Pengiriman selesai.'],
            ['SO-2026-029','QT-2026-009','2026-10-08','2026-11-08','Open','282/GA/IX/2026.TRIM','KURIR','Menunggu jadwal pengiriman.'],
            ['SO-2026-027','QT-2026-010','2026-09-30','2026-10-30','Processing','280/GA/IX/2026.TRIM','INSTALASI','Pekerjaan instalasi berjalan.'],
        ];

        foreach ($orders as [$number, $quotationNumber, $date, $deliveryDate, $status, $reference, $shipment, $notes]) {
            $quotation = DB::table('quotations')->where('quotation_number', $quotationNumber)->first();
            $customer = DB::table('customers')->where('customer_id', $quotation->customer_id)->first();
            $user = DB::table('users')->where('user_id', $salesId)->first();
            $values = [
                'customer_id' => $quotation->customer_id,
                'quotation_id' => $quotation->quotation_id,
                'created_by' => $salesId,
                'salesperson_user_id' => $salesId,
                'reference_number' => $reference,
                'sales_name_snapshot' => $user->name,
                'sold_to_name_snapshot' => $customer->name,
                'sold_to_address_snapshot' => $customer->address,
                'ship_to_name_snapshot' => $customer->name,
                'ship_to_address_snapshot' => $customer->address,
                'contact_phone_snapshot' => $customer->phone,
                'attention_snapshot' => 'Bagian Pengadaan',
                'payment_terms_snapshot' => $quotation->payment_terms_snapshot,
                'order_date' => $date,
                'delivery_date' => $deliveryDate,
                'shipment_method' => $shipment,
                'peripheral_notes' => $notes,
                'subtotal' => $quotation->subtotal,
                'discount_amount' => $quotation->discount_amount,
                'tax_rate' => $quotation->tax_rate,
                'tax_amount' => $quotation->tax_amount,
                'other_charges' => $quotation->other_charges,
                'total_amount' => $quotation->grand_total,
                'grand_total' => $quotation->grand_total,
                'status' => $status,
                'created_at' => $date . ' 10:00:00',
                'updated_at' => $date . ' 10:00:00',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancellation_reason' => null,
            ];

            $salesOrderId = DB::table('sales_orders')->where('so_number', $number)->value('sales_order_id');
            if ($salesOrderId) {
                DB::table('sales_orders')->where('sales_order_id', $salesOrderId)->update($values);
            } else {
                $salesOrderId = DB::table('sales_orders')->insertGetId(['so_number' => $number, ...$values], 'sales_order_id');
            }

            $quoteItems = DB::table('quotation_items')->where('quotation_id', $quotation->quotation_id)->get();
            foreach ($quoteItems as $quoteItem) {
                $lineDiscount = round((float) $quoteItem->quantity * (float) $quoteItem->unit_price * ((float) $quoteItem->discount / 100), 2);
                DB::table('sales_order_items')->updateOrInsert(
                    ['sales_order_id' => $salesOrderId, 'product_id' => $quoteItem->product_id],
                    [
                        'product_code_snapshot' => $quoteItem->product_code_snapshot,
                        'product_name_snapshot' => $quoteItem->product_name_snapshot,
                        'unit_snapshot' => $quoteItem->unit_snapshot,
                        'quantity_ordered' => $quoteItem->quantity,
                        'quantity_delivered' => 0,
                        'unit_price' => $quoteItem->unit_price,
                        'unit_cost_snapshot' => $quoteItem->unit_cost_snapshot,
                        'discount_amount' => $this->money($lineDiscount),
                        'subtotal' => $quoteItem->subtotal,
                    ]
                );
            }
        }
    }
}
