<?php

namespace App\Models\Finance;

use App\Enums\CashTransactionSource;
use App\Enums\CashTransactionStatus;
use App\Enums\CashTransactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $table = 'cash_transactions';

    protected $primaryKey = 'cash_transaction_id';

    public $timestamps = false;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'transaction_type',
        'source_type',
        'category',
        'description',
        'amount',
        'bank_account_id',
        'payment_id',
        'petty_cash_id',
        'recorded_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'amount' => 'decimal:2',
            'transaction_type' => CashTransactionType::class,
            'source_type' => CashTransactionSource::class,
            'status' => CashTransactionStatus::class,
        ];
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'bank_account_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by', 'user_id');
    }

    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', CashTransactionStatus::POSTED->value);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('transaction_number', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhere('category', 'like', $search);
            });
        }

        if (!empty($filters['type'])) {
            $query->where('transaction_type', $filters['type']);
        }

        if (!empty($filters['source'])) {
            $query->where('source_type', $filters['source']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['month'])) {
            $query->whereYear('transaction_date', substr($filters['month'], 0, 4))
                ->whereMonth('transaction_date', substr($filters['month'], 5, 2));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        return $query;
    }
}
