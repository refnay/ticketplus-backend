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

    public static function fromReference(string $transactionId, string $gateway): self
    {
        return new self(sprintf('%s::%s', $transactionId, $gateway));
    }
}
