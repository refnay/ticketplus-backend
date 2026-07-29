<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class ZoneCurrency extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
