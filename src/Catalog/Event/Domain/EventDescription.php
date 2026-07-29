<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class EventDescription extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
