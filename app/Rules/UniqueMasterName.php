<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

/**
 * Memastikan nama master data belum terdaftar (tanpa membedakan huruf besar/kecil).
 * Data yang sudah di-soft-delete diabaikan kecuali kolomnya unik di level database.
 */
class UniqueMasterName implements ValidationRule
{
    public function __construct(
        private readonly string $table,
        private readonly string $column,
        private readonly string $keyColumn,
        private readonly int|string|null $ignoreId = null,
        private readonly bool $includeDeleted = false,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)
            ->whereRaw('LOWER(' . $this->column . ') = ?', [mb_strtolower(trim((string) $value))]);

        if (!$this->includeDeleted) {
            $query->whereNull('deleted_at');
        }

        if ($this->ignoreId !== null) {
            $query->where($this->keyColumn, '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('Nama ini sudah terdaftar.');
        }
    }
}
