<?php

namespace App\Sale\Purchase\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class PurchaseStatus extends IntValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
