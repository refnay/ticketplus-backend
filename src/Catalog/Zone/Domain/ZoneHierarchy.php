<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class ZoneHierarchy extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
