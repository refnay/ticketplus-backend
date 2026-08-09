<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class OrderStatus extends IntValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
