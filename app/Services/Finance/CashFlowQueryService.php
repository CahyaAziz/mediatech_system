<?php

namespace App\Services\Finance;

use App\Models\Finance\CashTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CashFlowQueryService
{
    private const VALID_PER_PAGE = [5, 10, 25, 50];

    public function normalizedFilters(array $filters): array
    {
        $perPage = (int) ($filters['per_page'] ?? 10);
        if (!in_array($perPage, self::VALID_PER_PAGE, true)) {
            $perPage = 10;
        }

        $month = $filters['month'] ?? null;
        if ($month !== null && !preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
            $month = null;
        }

        // Bulan masa depan tidak diizinkan (mis. sekarang 2026-11, 2026-12 tidak muncul).
        if ($month !== null && $month > now()->format('Y-m')) {
            $month = null;
        }

        return [
            'search' => trim((string) ($filters['search'] ?? '')),
            'type' => $filters['type'] ?? null,
            'source' => $filters['source'] ?? null,
            'status' => $filters['status'] ?? null,
            'month' => $month,
            'date_from' => $filters['date_from'] ?? null,
            'date_to' => $filters['date_to'] ?? null,
            'per_page' => $perPage,
        ];
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $filters = $this->normalizedFilters($filters);

        return CashTransaction::query()
            ->with(['bankAccount', 'recordedBy'])
            ->filter($filters)
            ->orderByDesc('transaction_date')
            ->orderByDesc('cash_transaction_id')
            ->paginate($filters['per_page'])
            ->withQueryString();
    }

    public function summary(array $filters): array
    {
        $base = CashTransaction::query()->filter($filters)->posted();

        $cashIn = (clone $base)->where('transaction_type', 'Cash In')->sum('amount');
        $cashOut = (clone $base)->where('transaction_type', 'Cash Out')->sum('amount');

        return [
            'cash_in' => (float) $cashIn,
            'cash_out' => (float) $cashOut,
            'net' => (float) $cashIn - (float) $cashOut,
            'count' => (clone $base)->count(),
        ];
    }

    public function monthsWithData(): Collection
    {
        $currentMonth = now()->format('Y-m');

        $dateFormat = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', transaction_date)"
            : "DATE_FORMAT(transaction_date, '%Y-%m')";

        return CashTransaction::query()
            ->selectRaw("{$dateFormat} as month")
            ->distinct()
            ->orderByDesc('month')
            ->pluck('month')
            ->filter(fn ($month) => $month !== null && $month <= $currentMonth)
            ->values()
            ->map(fn ($month) => [
                'value' => $month,
                'label' => Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y'),
            ]);
    }

    public function exportRows(array $filters): Builder
    {
        $filters = $this->normalizedFilters($filters);

        return CashTransaction::query()
            ->with(['bankAccount', 'recordedBy'])
            ->filter($filters)
            ->posted()
            ->orderByDesc('transaction_date')
            ->orderByDesc('cash_transaction_id');
    }
}