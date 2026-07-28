<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\DateValueObject;
use Override;

class EventDayDate extends DateValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
