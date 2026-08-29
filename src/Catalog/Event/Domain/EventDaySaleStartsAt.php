<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class EventDaySaleStartsAt extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
