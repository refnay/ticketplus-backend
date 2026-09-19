<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class TicketValidatedAt extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}