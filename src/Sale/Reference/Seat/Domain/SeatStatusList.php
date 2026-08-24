<?php

namespace App\Sale\Reference\Seat\Domain;

enum SeatStatusList: int
{
    case AVAILABLE = 0;
    case RESERVED = 1;
    case SOLD = 2;
    case BLOCKED = 3;
    case UNAVAILABLE = 4;

    public function sameValue(int $value): bool
    {
        return $this->value === $value;
    }
}
