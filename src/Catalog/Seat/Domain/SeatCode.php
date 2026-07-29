<?php

namespace App\Catalog\Seat\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class SeatCode extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
