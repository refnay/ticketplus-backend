<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class TicketStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function active(): self
    {
        return new self(TicketStatusList::ACTIVE->value);
    }
}
