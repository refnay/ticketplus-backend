<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class EventCoordinates extends ArrayValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
