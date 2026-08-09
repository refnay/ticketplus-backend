<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class DiscountEndDate extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
