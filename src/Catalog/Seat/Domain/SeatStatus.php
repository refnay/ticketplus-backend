<?php

namespace App\Catalog\Seat\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class SeatStatus extends IntValueObject
{
    public static function available(): self
    {
        return new self(SeatStatusList::AVAILABLE->value);
    }

    public static function reserved(): self
    {
        return new self(SeatStatusList::RESERVED->value);
    }

    public static function sold(): self
    {
        return new self(SeatStatusList::SOLD->value);
    }

    public function isReserved(): bool
    {
        return $this->value === SeatStatusList::RESERVED->value;
    }

    public function isAvailable(): bool
    {
        return $this->value === SeatStatusList::AVAILABLE->value;
    }

    #[Override]
    public function validate(): void
    {
    }
}
