<?php

namespace App\Sale\Purchase\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class PurchaseSubTotal extends FloatValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
