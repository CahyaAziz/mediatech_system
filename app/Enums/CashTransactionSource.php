<?php

namespace App\Enums;

enum CashTransactionSource: string
{
    case PAYMENT = 'PAYMENT';
    case PETTY_CASH = 'PETTY_CASH';
    case MANUAL = 'MANUAL';

    public function label(): string
    {
        return match ($this) {
            self::PAYMENT => 'Payment',
            self::PETTY_CASH => 'Petty Cash',
            self::MANUAL => 'Manual',
        };
    }
}
