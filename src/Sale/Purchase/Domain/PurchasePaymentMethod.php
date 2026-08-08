<?php

namespace App\Sale\Purchase\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class PurchasePaymentMethod extends IntValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
