<?php

namespace App\Models\Inventory;

use App\Enums\ProductType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** Satuan yang tersedia pada form produk (sumber: prototype v3). */
    public const UNITS = ['Unit', 'Pcs', 'Set', 'Paket', 'Titik', 'Bulan'];

    protected $table = 'products';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillable = [
        'sku',
        'product_name',
        'category_id',
        'product_type',
        'unit',
        'selling_price',
        'purchase_price',
        'min_stock',
        'track_serial',
        'is_active',
        'deleted_at',
        'deleted_by',
        'deletion_reason',
    ];

    protected function casts(): array
    {
        return [
            'product_type' => ProductType::class,
            'selling_price' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'min_stock' => 'decimal:3',
            'track_serial' => 'boolean',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'category_id');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_id');
    }
}
