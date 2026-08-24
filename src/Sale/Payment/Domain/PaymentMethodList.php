<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Payment\Domain\Exceptions\PaymentMethodNotSupported;

enum PaymentMethodList: int
{
    case UNDEFINED = -1;
    case CREDIT_CARD = 0;
    case DEBIT_CARD = 1;
    case YAPE = 2;
    case PLIN = 3;
    case TRANSFER = 4;
    case CASH = 5;

    public function toMercadoPago(): string
    {
        return match ($this) {
            self::CREDIT_CARD => 'credit_card',
            self::DEBIT_CARD => 'debit_card',
            self::YAPE => 'yape',
            self::PLIN => 'plin',
            self::TRANSFER => 'transfer',
            self::CASH => 'cash',
            self::UNDEFINED => throw new PaymentMethodNotSupported(),
        };
    }
}
