<?php

namespace App\Sale\Reference\Zone\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class ZoneId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
