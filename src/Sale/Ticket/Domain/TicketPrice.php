<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class TicketPrice extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
