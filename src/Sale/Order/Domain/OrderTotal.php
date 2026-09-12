<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class OrderTotal extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
