<?php

namespace App\Sale\Order\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class OrderZoneReservedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $dayId,
        private string $zoneId,
        private int $quantity,
    ) {
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }
    
    public function zoneId(): string
    {
        return $this->zoneId;
    }

    public function payload(): array
    {
        return [
            'dayId' => $this->dayId,
            'zoneId' => $this->zoneId,
            'quantity' => $this->quantity,
        ];
    }
}
