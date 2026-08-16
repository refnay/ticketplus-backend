<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class PaymentPayer extends ArrayValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
