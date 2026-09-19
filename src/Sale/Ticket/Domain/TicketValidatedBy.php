<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class TicketValidatedBy extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}