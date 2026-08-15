<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class OrderExpiresAt extends DateTimeValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
