<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class OrderSubTotal extends FloatValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
