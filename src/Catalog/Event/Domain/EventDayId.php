<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class EventDayId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
