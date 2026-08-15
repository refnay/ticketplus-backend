<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class DiscountType extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public function isPercentage(): bool
    {
        return DiscountTypeList::PERCENTAGE->value === $this->value;
    }
}
