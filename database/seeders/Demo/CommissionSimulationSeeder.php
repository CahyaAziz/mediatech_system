<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionSimulationSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $salespersonId = $this->userId('rafli@meditech.co.id');
        $rules = DB::table('commission_rules')->where('is_active', true)->orderBy('priority')->get();

        $orders = DB::table('sales_orders')->whereNotNull('salesperson_user_id')->get();
        foreach ($orders as $order) {
            $items = DB::table('sales_order_items')->where('sales_order_id', $order->sales_order_id)->get();
            $revenue = 0.0;
            $cost = 0.0;
            foreach ($items as $item) {
                $revenue += (float) $item->subtotal;
                $cost += (float) $item->quantity_ordered * (float) $item->unit_cost_snapshot;
            }
            $profit = max(0, $revenue - $cost);

            // Pilih satu aturan berdasarkan nilai basis dan priority. Aturan profit diprioritaskan jika memenuhi ambang.
            $basisType = $profit >= 10000000 ? 'gross_profit' : 'sales';
            $basisAmount = $basisType === 'gross_profit' ? $profit : $revenue;
            $selectedRule = $rules->filter(fn ($rule) =>
                $rule->basis_type === $basisType
                && (float) $rule->minimum_basis_amount <= $basisAmount
                && ($rule->maximum_basis_amount === null || (float) $rule->maximum_basis_amount >= $basisAmount)
            )->sortByDesc('priority')->first();

            if (!$selectedRule) {
                continue;
            }

            $commissionAmount = $selectedRule->calculation_type === 'percentage'
                ? round($basisAmount * ((float) $selectedRule->commission_value / 100), 2)
                : round((float) $selectedRule->commission_value, 2);

            DB::table('commission_payouts')->updateOrInsert(
                ['sales_order_id' => $order->sales_order_id, 'salesperson_user_id' => $order->salesperson_user_id],
                [
                    'rule_id' => $selectedRule->rule_id,
                    'basis_type_snapshot' => $basisType,
                    'basis_amount' => $this->money($basisAmount),
                    'calculation_type_snapshot' => $selectedRule->calculation_type,
                    'commission_value_snapshot' => number_format((float) $selectedRule->commission_value, 4, '.', ''),
                    'commission_amount' => $this->money($commissionAmount),
                    'status' => 'Calculated',
                    'calculated_at' => $order->order_date . ' 17:00:00',
                    'approved_by' => null,
                    'approved_at' => null,
                    'paid_at' => null,
                ]
            );
        }
    }
}