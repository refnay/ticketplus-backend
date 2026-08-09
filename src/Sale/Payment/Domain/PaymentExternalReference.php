<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class PaymentExternalReference extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
