<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class TicketQRCode extends UuidValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
