<?php

namespace App\Services\Audit;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditLogService
{
    public function record(
        ?User $actor,
        string $action,
        string $entityName,
        string|int $entityId,
        string $description,
        array $oldValues = [],
        array $newValues = [],
        ?string $reason = null
    ): void {
        $request = app()->bound('request') ? request() : null;

        DB::table('audit_logs')->insert([
            'user_id' => $actor?->user_id,
            'action' => $action,
            'entity_name' => $entityName,
            'entity_id' => (string) $entityId,
            'created_at' => now(),
            'description' => $description,
            'old_values' => json_encode($oldValues, JSON_THROW_ON_ERROR),
            'new_values' => json_encode($newValues, JSON_THROW_ON_ERROR),
            'reason' => $reason,
            'ip_address' => substr((string) ($request?->ip() ?? '127.0.0.1'), 0, 45),
            'user_agent' => substr((string) ($request?->userAgent() ?? 'console'), 0, 500),
            'request_id' => (string) ($request?->attributes->get('request_id') ?? Str::uuid()),
        ]);
    }
}
