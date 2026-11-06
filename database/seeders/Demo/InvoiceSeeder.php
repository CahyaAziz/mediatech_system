<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $financeId = $this->userId('ica.apriyanti@meditech.co.id');
        $invoices = [
            ['INV-2026-024','SO-2026-029','2026-10-16','2026-10-30','Termin','Partially Paid','Invoice untuk pengiriman bertahap.'],
            ['INV-2026-023','SO-2026-031','2026-10-15','2026-10-29','Termin','Paid','Pembayaran telah tervalidasi.'],
            ['INV-2026-022','SO-2026-027','2026-10-12','2026-10-26','Termin','Outstanding','Menunggu pembayaran customer.'],
            ['INV-2026-021','SO-2026-030','2026-10-01','2026-10-15','Barang keluar','Overdue','Melewati tanggal jatuh tempo.'],
        ];

        foreach ($invoices as [$number, $soNumber, $invoiceDate, $dueDate, $type, $status, $notes]) {
            $order = DB::table('sales_orders')->where('so_number', $soNumber)->first();
            $customer = DB::table('customers')->where('customer_id', $order->customer_id)->first();
            $salesperson = DB::table('users')->where('user_id', $order->salesperson_user_id)->first();
            $values = [
                'sales_order_id' => $order->sales_order_id,
                'created_by' => $financeId,
                'reference_number' => $order->reference_number,
                'sales_name_snapshot' => $salesperson?->name ?? 'Sales',
                'bill_to_snapshot' => $order->sold_to_name_snapshot,
                'bill_to_address_snapshot' => $order->sold_to_address_snapshot,
                'ship_to_snapshot' => $order->ship_to_name_snapshot,
                'ship_to_address_snapshot' => $order->ship_to_address_snapshot,
                'contact_phone_snapshot' => $customer->phone,
                'attention_snapshot' => $order->attention_snapshot,
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'tax_rate' => $order->tax_rate,
                'tax_amount' => $order->tax_amount,
                'other_charges' => $order->other_charges,
                'grand_total' => $order->grand_total,
                'payment_terms_snapshot' => $order->payment_terms_snapshot,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'invoice_type' => $type,
                'status' => $status,
                'total_amount' => $order->grand_total,
                'notes' => $notes,
                'created_at' => $invoiceDate . ' 10:30:00',
                'updated_at' => $invoiceDate . ' 10:30:00',
            ];

            DB::table('invoices')->updateOrInsert(['invoice_number' => $number], $values);
            $invoiceId = (int) DB::table('invoices')->where('invoice_number', $number)->value('invoice_id');

            $orderItems = DB::table('sales_order_items')->where('sales_order_id', $order->sales_order_id)->get();
            foreach ($orderItems as $item) {
                DB::table('invoice_items')->updateOrInsert(
                    ['invoice_id' => $invoiceId, 'so_item_id' => $item->so_item_id],
                    [
                        'product_code_snapshot' => $item->product_code_snapshot,
                        'product_name_snapshot' => $item->product_name_snapshot,
                        'unit_snapshot' => $item->unit_snapshot,
                        'quantity' => $item->quantity_ordered,
                        'unit_price' => $item->unit_price,
                        'discount_amount' => $item->discount_amount,
                        'subtotal' => $item->subtotal,
                    ]
                );
            }
        }
    }
}
