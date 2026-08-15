<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class OrderDetails extends ArrayValueObject  
{
    #[Override]
    public function validate(): void
    {
    }
}
