<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\TimeValueObject;
use Override;

class EventDayStartTime extends TimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
