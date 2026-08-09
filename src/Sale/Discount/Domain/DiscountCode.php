<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class DiscountCode extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
