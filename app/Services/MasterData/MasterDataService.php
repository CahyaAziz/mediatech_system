<?php

namespace App\Services\MasterData;

use App\Models\CRM\Customer;
use App\Models\CRM\Supplier;
use App\Models\Finance\BankAccount;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Warehouse;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Proses bersama untuk seluruh tab Master Data: definisi tab, kode otomatis,
 * simpan/ubah, soft delete dengan pemeriksaan dependensi, dan audit log.
 */
class MasterDataService
{
    /**
     * Definisi tab mengikuti urutan prototype v3.
     * `usage` = tabel/kolom yang membuat data tidak boleh dihapus selama masih dipakai.
     */
    private const TABS = [
        'customers' => [
            'model' => Customer::class,
            'label' => 'Customer',
            'plural' => 'Customers',
            'icon' => 'i-user',
            'entity' => 'CUSTOMERS',
            'code_column' => 'customer_code',
            'name_column' => 'name',
            'prefix' => 'CUS-',
            'pad' => 4,
            'description' => 'Perusahaan dan instansi pelanggan (perbankan, oil & gas, manufaktur, distribusi, retail, rumah sakit, pendidikan).',
            'usage' => [['quotations', 'customer_id'], ['sales_orders', 'customer_id']],
        ],
        'suppliers' => [
            'model' => Supplier::class,
            'label' => 'Supplier',
            'plural' => 'Suppliers',
            'icon' => 'i-handshake',
            'entity' => 'SUPPLIERS',
            'code_column' => 'supplier_code',
            'name_column' => 'name',
            'prefix' => 'SUP-',
            'pad' => 4,
            'description' => 'Distributor/pemasok barang yang tercatat pada penerimaan barang (Goods In).',
            'usage' => [['goods_receipts', 'supplier_id']],
        ],
        'products' => [
            'model' => Product::class,
            'label' => 'Produk',
            'plural' => 'Products',
            'icon' => 'i-inventory',
            'entity' => 'PRODUCTS',
            'code_column' => 'sku',
            'name_column' => 'product_name',
            'prefix' => 'PRD-',
            'pad' => 3,
            'description' => 'Barang (PC, laptop, printer, projector, CCTV) dan jasa (instalasi, maintenance). Stok hanya berlaku untuk jenis Barang. Produk nonaktif tidak muncul di quotation.',
            'usage' => [],
        ],
        'categories' => [
            'model' => ProductCategory::class,
            'label' => 'Kategori',
            'plural' => 'Kategori',
            'icon' => 'i-tag',
            'entity' => 'PRODUCT_CATEGORIES',
            'code_column' => 'category_code',
            'name_column' => 'category_name',
            'prefix' => 'CAT-',
            'pad' => 3,
            'description' => 'Pengelompokan produk dan jasa.',
            'usage' => [['products', 'category_id', true]],
        ],
        'warehouses' => [
            'model' => Warehouse::class,
            'label' => 'Gudang',
            'plural' => 'Warehouses',
            'icon' => 'i-building',
            'entity' => 'WAREHOUSES',
            'code_column' => 'warehouse_code',
            'name_column' => 'warehouse_name',
            'prefix' => 'WH-',
            'pad' => 3,
            'description' => 'Lokasi penyimpanan barang.',
            'usage' => [['goods_receipts', 'warehouse_id'], ['goods_issue_items', 'warehouse_id'], ['stock_movements', 'warehouse_id'], ['product_serials', 'warehouse_id']],
        ],
        'bank-accounts' => [
            'model' => BankAccount::class,
            'label' => 'Rekening',
            'plural' => 'Rekening',
            'icon' => 'i-bank',
            'entity' => 'BANK_ACCOUNTS',
            'code_column' => 'account_code',
            'name_column' => 'account_number',
            'prefix' => 'BNK-',
            'pad' => 3,
            'description' => 'Rekening perusahaan untuk penerimaan dan pengeluaran. Perubahan dibatasi.',
            'usage' => [['payments', 'bank_account_id'], ['petty_cash_transactions', 'bank_account_id'], ['cash_transactions', 'bank_account_id']],
        ],
    ];

    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function definition(string $tab): array
    {
        abort_unless(isset(self::TABS[$tab]), 404);

        return ['key' => $tab, ...self::TABS[$tab]];
    }

    /** Daftar tab beserta jumlah data yang belum dihapus. */
    public function tabs(): array
    {
        return collect(self::TABS)->map(fn (array $tab, string $key) => [
            'key' => $key,
            'plural' => $tab['plural'],
            'icon' => $tab['icon'],
            'route' => "master-data.{$key}.index",
            'count' => $tab['model']::query()->notDeleted()->count(),
        ])->values()->all();
    }

    public function nextCode(string $tab): string
    {
        $definition = $this->definition($tab);
        $column = $definition['code_column'];

        $max = $definition['model']::query()
            ->where($column, 'like', $definition['prefix'] . '%')
            ->pluck($column)
            ->map(fn (string $code) => (int) preg_replace('/\D/', '', $code))
            ->max() ?? 0;

        return $definition['prefix'] . str_pad((string) ($max + 1), $definition['pad'], '0', STR_PAD_LEFT);
    }

    public function create(string $tab, array $data, User $actor): Model
    {
        $definition = $this->definition($tab);

        return DB::transaction(function () use ($tab, $definition, $data, $actor) {
            $record = $definition['model']::query()->create([
                ...$data,
                $definition['code_column'] => $this->nextCode($tab),
                'is_active' => $data['is_active'] ?? true,
            ]);

            $this->audit->record($actor, 'CREATE_MASTER_DATA', $definition['entity'], $record->getKey(),
                $this->displayName($definition, $record) . ' ditambahkan.', [], $record->toArray());

            return $record;
        });
    }

    public function update(string $tab, Model $record, array $data, User $actor): Model
    {
        $definition = $this->definition($tab);
        $this->ensureNotDeleted($record);

        return DB::transaction(function () use ($definition, $record, $data, $actor) {
            // Kode tidak boleh berubah setelah dibuat.
            unset($data[$definition['code_column']]);

            $old = $record->getOriginal();
            $record->fill($data)->save();

            $this->audit->record($actor, 'UPDATE_MASTER_DATA', $definition['entity'], $record->getKey(),
                $this->displayName($definition, $record) . ' diperbarui.',
                array_intersect_key($old, $record->getChanges()), $record->getChanges());

            return $record;
        });
    }

    public function toggleActive(string $tab, Model $record, User $actor): Model
    {
        $definition = $this->definition($tab);
        $this->ensureNotDeleted($record);

        return DB::transaction(function () use ($definition, $record, $actor) {
            $wasActive = (bool) $record->is_active;
            $record->forceFill(['is_active' => !$wasActive])->save();

            $this->audit->record($actor, 'UPDATE_MASTER_DATA', $definition['entity'], $record->getKey(),
                $this->displayName($definition, $record) . ($wasActive ? ' dinonaktifkan.' : ' diaktifkan.'),
                ['is_active' => $wasActive], ['is_active' => !$wasActive]);

            return $record;
        });
    }

    public function softDelete(string $tab, Model $record, string $reason, User $actor): void
    {
        $definition = $this->definition($tab);
        $this->ensureNotDeleted($record);

        if ($this->isInUse($definition, $record)) {
            throw ValidationException::withMessages([
                'deletion_reason' => $this->displayName($definition, $record) . ' masih dipakai data lain, jadi belum bisa dihapus. Nonaktifkan data ini bila tidak ingin dipakai lagi.',
            ]);
        }

        DB::transaction(function () use ($definition, $record, $reason, $actor) {
            $record->forceFill([
                'deleted_at' => now(),
                'deleted_by' => $actor->user_id,
                'deletion_reason' => $reason,
            ])->save();

            $this->audit->record($actor, 'SOFT_DELETE_MASTER_DATA', $definition['entity'], $record->getKey(),
                $this->displayName($definition, $record) . ' dihapus sementara.', [], [], $reason);
        });
    }

    public function displayName(array $definition, Model $record): string
    {
        if ($definition['key'] === 'bank-accounts') {
            return $record->bank_name . ' ' . $record->account_number;
        }

        return (string) $record->{$definition['name_column']};
    }

    private function isInUse(array $definition, Model $record): bool
    {
        foreach ($definition['usage'] as $usage) {
            [$table, $column] = $usage;
            $query = DB::table($table)->where($column, $record->getKey());

            if ($usage[2] ?? false) {
                $query->whereNull('deleted_at');
            }

            if ($query->exists()) {
                return true;
            }
        }

        return false;
    }

    private function ensureNotDeleted(Model $record): void
    {
        abort_if($record->deleted_at !== null, 404);
    }
}
