<?php

namespace App\Sale\Seat\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class SeatId extends UuidValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
