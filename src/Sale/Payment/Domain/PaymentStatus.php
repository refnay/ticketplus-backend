<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class PaymentStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(PaymentStatusList::PENDING->value);
    }
}
