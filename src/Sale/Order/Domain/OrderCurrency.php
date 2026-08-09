<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class OrderCurrency extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
