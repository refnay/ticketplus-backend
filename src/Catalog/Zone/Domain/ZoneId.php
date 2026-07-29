<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class ZoneId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
