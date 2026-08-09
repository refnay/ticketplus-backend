<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class DiscountValue extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
