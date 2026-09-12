<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class OrderId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
