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

    #[Override]
    public function validate(): void
    {
    }
}
