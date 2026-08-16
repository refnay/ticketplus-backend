<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class EventTaxRate extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
