<?php

namespace App\Sale\Discount\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class DiscountId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
