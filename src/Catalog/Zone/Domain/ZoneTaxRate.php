<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class ZoneTaxRate extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
