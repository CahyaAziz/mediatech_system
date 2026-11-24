<?php

namespace App\Models\CRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    /** Sektor industri pada form customer (sumber: prototype v3). */
    public const SECTORS = [
        'Perbankan',
        'Oil & Gas',
        'Manufaktur',
        'Distribusi',
        'Retail',
        'Rumah sakit',
        'Pendidikan',
        'Lainnya',
    ];

    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    public $timestamps = false;

    protected $fillable = [
        'customer_code',
        'name',
        'industry_sector',
        'address',
        'phone',
        'email',
        'is_active',
        'deleted_at',
        'deleted_by',
        'deletion_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_id');
    }
}
