<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class PaymentMethod extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
