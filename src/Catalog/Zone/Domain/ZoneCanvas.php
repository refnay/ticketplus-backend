<?php

namespace App\Catalog\Zone\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class ZoneCanvas extends ArrayValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
