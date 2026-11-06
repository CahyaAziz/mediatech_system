<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $records = [
            ['2026-10-18 10:42:00','ica.apriyanti@meditech.co.id','VALIDATE_PAYMENT','PAYMENTS','PAY-2026-041','Pembayaran tervalidasi dan cash-in dibuat otomatis.', ['status'=>'Pending Validation'], ['status'=>'Validated'], 'DEMO-AUDIT-001'],
            ['2026-10-18 10:18:00','regina.fadhilah@meditech.co.id','APPROVE_QUOTATION','QUOTATIONS','QT-2026-017','Quotation disetujui untuk diproses menjadi Sales Order.', ['status'=>'Pending Approval'], ['status'=>'Approved'], 'DEMO-AUDIT-002'],
            ['2026-10-18 09:54:00','rafli.ahmad@meditech.co.id','SUBMIT_QUOTATION','QUOTATIONS','QT-2026-018','Quotation diajukan untuk approval.', ['status'=>'Draft'], ['status'=>'Pending Approval'], 'DEMO-AUDIT-003'],
            ['2026-10-17 16:12:00','dzaky.julian@meditech.co.id','GOODS_OUT','STOCK_MOVEMENTS','GI-2026-058','10 unit Kamera CCTV Hikvision 2MP dikeluarkan untuk SO-2026-031.', ['available_stock'=>24], ['available_stock'=>14], 'DEMO-AUDIT-004'],
            ['2026-10-17 14:03:00','mirachel.cindejona@meditech.co.id','UPDATE_USER','USERS','nugra.hasahatan@meditech.co.id','Status pengguna Nugra Hasahatan diperbarui.', ['is_active'=>true], ['is_active'=>false], 'DEMO-AUDIT-005'],
        ];

        foreach ($records as [$createdAt, $email, $action, $entity, $entityId, $description, $oldValues, $newValues, $requestId]) {
            $userId = $this->userId($email);
            if (!DB::table('audit_logs')->where('request_id', $requestId)->exists()) {
                DB::table('audit_logs')->insert([
                    'user_id' => $userId,
                    'action' => $action,
                    'entity_name' => $entity,
                    'entity_id' => $entityId,
                    'created_at' => $createdAt,
                    'description' => $description,
                    'old_values' => json_encode($oldValues, JSON_UNESCAPED_UNICODE),
                    'new_values' => json_encode($newValues, JSON_UNESCAPED_UNICODE),
                    'reason' => null,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Meditech Demo Seeder',
                    'request_id' => $requestId,
                ]);
            }
        }

        $histories = [
            ['QUOTATIONS','QT-2026-017','Pending Approval','Approved','regina.fadhilah@meditech.co.id','2026-10-18 10:18:00','Quotation disetujui.','DEMO-STATUS-001'],
            ['QUOTATIONS','QT-2026-018','Draft','Pending Approval','rafli.ahmad@meditech.co.id','2026-10-18 09:54:00','Diajukan untuk approval.','DEMO-STATUS-002'],
            ['QUOTATIONS','QT-2026-016','Pending Approval','Rejected','regina.fadhilah@meditech.co.id','2026-10-15 15:00:00','Mohon tinjau kembali diskon dan masa berlaku garansi.','DEMO-STATUS-003'],
            ['SALES_ORDERS','SO-2026-031','Open','Processing','dzaky.julian@meditech.co.id','2026-10-17 16:12:00','Pengiriman sebagian telah diposting.','DEMO-STATUS-004'],
            ['PAYMENTS','PAY-2026-041','Pending Validation','Validated','cahya.aziz@meditech.co.id','2026-10-18 10:42:00','Pembayaran divalidasi.','DEMO-STATUS-005'],
        ];
        foreach ($histories as [$entity, $entityId, $old, $new, $email, $changedAt, $reason, $requestId]) {
            if (!DB::table('transaction_status_history')->where('request_id', $requestId)->exists()) {
                DB::table('transaction_status_history')->insert([
                    'entity_name' => $entity,
                    'entity_id' => $entityId,
                    'old_status' => $old,
                    'new_status' => $new,
                    'changed_by' => $this->userId($email),
                    'changed_at' => $changedAt,
                    'reason' => $reason,
                    'request_id' => $requestId,
                ]);
            }
        }
    }
}
