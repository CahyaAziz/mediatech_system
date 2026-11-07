<?php

namespace App\Enums;

enum CashTransactionStatus: string
{
    case POSTED = 'Posted';
    case REVERSED = 'Reversed';

    public function label(): string
    {
        return $this->value;
    }
}
