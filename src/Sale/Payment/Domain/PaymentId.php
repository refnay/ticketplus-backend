<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class PaymentId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
