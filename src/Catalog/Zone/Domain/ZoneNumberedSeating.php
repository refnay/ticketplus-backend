<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\BooleanValueObject;
use Override;

class ZoneNumberedSeating extends BooleanValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
