<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\BooleanValueObject;
use Override;

class DiscountActive extends BooleanValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
