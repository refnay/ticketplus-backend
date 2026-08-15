<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class OrderPaymentMethod extends IntValueObject  
{
    #[Override]
    public function validate(): void
    {
    }

    public static function undefined(): self
    {
        return new self (OrderPaymentMethodList::UNDEFINED->value);
    }
}
