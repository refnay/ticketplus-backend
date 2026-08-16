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

    public static function fromReference(string $id, string $provider): self
    {
        return new self(sprintf('%s::%s', $id, $provider));
    }
}
