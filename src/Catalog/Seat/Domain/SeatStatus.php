<?php

namespace App\Catalog\Seat\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class SeatStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
