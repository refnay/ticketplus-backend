<?php

namespace App\Sale\Purchase\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class PurchaseCurrency extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
