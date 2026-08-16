<?php

namespace App\Sale\Payment\Domain;

enum PaymentStatusList: int
{
    case PENDING = 0;
    case PROCESSING = 1;
    case APPROVED = 2;
    case DECLINED = 3;

    public static function fromMercadoPago(string $status): self
    {
        return match ($status) {
            'pending' => self::PENDING,
            'approved' => self::APPROVED,
            'rejected' => self::DECLINED,
            default => self::PROCESSING,
        };
    }
}
