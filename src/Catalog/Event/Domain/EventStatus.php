<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class EventStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
