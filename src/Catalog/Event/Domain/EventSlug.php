<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class EventSlug extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
