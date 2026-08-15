<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class DiscountStartDate extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public function isEffective(): bool
    {
        return !$this->after(DiscountStartDate::now());
    }
}
