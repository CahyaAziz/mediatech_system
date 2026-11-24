<?php

namespace App\Enums;

enum ProductType: string
{
    case GOODS = 'Barang';
    case SERVICE = 'Jasa';

    public function label(): string
    {
        return $this->value;
    }

    /** Stok, stok minimum, dan nomor serial hanya berlaku untuk barang. */
    public function tracksStock(): bool
    {
        return $this === self::GOODS;
    }
}
