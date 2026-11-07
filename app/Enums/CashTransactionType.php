<?php

namespace App\Enums;

enum CashTransactionType: string
{
    case CASH_IN = 'Cash In';
    case CASH_OUT = 'Cash Out';

    public function isIn(): bool
    {
        return $this === self::CASH_IN;
    }

    public function label(): string
    {
        return $this->value;
    }
}
