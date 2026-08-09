<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\FloatValueObject;
use Override;

class PaymentAmount extends FloatValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
