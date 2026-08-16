<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class OrderStatus extends IntValueObject  
{
    #[Override]
    public function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(OrderStatusList::PENDING->value); 
    }

    public function isPending(): bool
    {
        return OrderStatusList::PENDING->value == $this->value;
    }
}
