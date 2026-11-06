<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodsOutSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $warehouseUserId = $this->userId('dzaky.julian@meditech.co.id');
        $issues = [
            ['GI-2026-058', 'SO-2026-031', '2026-10-17', 'Pengiriman sebagian 10 unit Kamera CCTV Hikvision 2MP.', ['PRD-011' => 10]],
            ['GI-2026-057', 'SO-2026-030', '2026-10-12', 'Pengiriman 5 unit Projector NEC NP-M350X.', ['PRD-010' => 5]],
        ];

        foreach ($issues as [$number, $soNumber, $date, $notes, $items]) {
            $salesOrderId = $this->salesOrderId($soNumber);
            DB::table('goods_issues')->updateOrInsert(
                ['goods_issue_number' => $number],
                [
                    'sales_order_id' => $salesOrderId,
                    'created_by' => $warehouseUserId,
                    'created_at' => $date . ' 15:00:00',
                    'posted_at' => $date . ' 16:00:00',
                    'status' => 'Posted',
                    'notes' => $notes,
                    'reversed_by' => null,
                    'reversed_at' => null,
                    'reversal_reason' => null,
                    'deleted_at' => null,
                    'deleted_by' => null,
                    'deletion_reason' => null,
                ]
            );
            $goodsIssueId = (int) DB::table('goods_issues')->where('goods_issue_number', $number)->value('goods_issue_id');

            foreach ($items as $sku => $quantity) {
                $productId = $this->productId($sku);
                $soItem = DB::table('sales_order_items')
                    ->where('sales_order_id', $salesOrderId)
                    ->where('product_id', $productId)
                    ->first();
                if (!$soItem) {
                    continue;
                }

                $warehouseId = $this->warehouseId('WH-001');
                DB::table('goods_issue_items')->updateOrInsert(
                    ['goods_issue_id' => $goodsIssueId, 'so_item_id' => $soItem->so_item_id, 'warehouse_id' => $warehouseId],
                    ['quantity_issued' => $quantity, 'unit' => $soItem->unit_snapshot, 'notes' => $notes]
                );
                $issueItemId = (int) DB::table('goods_issue_items')
                    ->where('goods_issue_id', $goodsIssueId)
                    ->where('so_item_id', $soItem->so_item_id)
                    ->where('warehouse_id', $warehouseId)
                    ->value('goods_issue_item_id');

                $movement = DB::table('stock_movements')->where('goods_issue_item_id', $issueItemId)->where('movement_type', 'GOODS_OUT')->first();
                $movementValues = [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'receipt_item_id' => null,
                    'goods_issue_item_id' => $issueItemId,
                    'serial_id' => null,
                    'reversal_of_movement_id' => null,
                    'recorded_by' => $warehouseUserId,
                    'movement_date' => $date . ' 16:00:00',
                    'movement_type' => 'GOODS_OUT',
                    'quantity' => $quantity,
                    'reference_number' => $number,
                    'adjustment_reason' => null,
                ];
                if ($movement) {
                    DB::table('stock_movements')->where('movement_id', $movement->movement_id)->update($movementValues);
                } else {
                    DB::table('stock_movements')->insert($movementValues);
                }

                DB::table('sales_order_items')->where('so_item_id', $soItem->so_item_id)
                    ->update(['quantity_delivered' => min((float) $soItem->quantity_ordered, (float) $quantity)]);
            }
        }

        // Baris jasa SO-030 juga ditandai selesai tanpa stock movement karena bukan barang gudang.
        $so30 = $this->salesOrderId('SO-2026-030');
        $serviceId = $this->productId('PRD-017');
        DB::table('sales_order_items')->where('sales_order_id', $so30)->where('product_id', $serviceId)
            ->update(['quantity_delivered' => DB::raw('quantity_ordered')]);
    }
}
