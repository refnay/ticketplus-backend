<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class EventName extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
