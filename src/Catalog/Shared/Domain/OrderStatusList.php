<?php

namespace App\Catalog\Shared\Domain;

enum OrderStatusList: int
{
    case PENDING = 0;
    case PAID= 1;
    case CANCELLED = 2;
    case REFUNDED = 3;
    case EXPIRED = 4;
}
