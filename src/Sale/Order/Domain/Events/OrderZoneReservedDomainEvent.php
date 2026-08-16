<?php

namespace App\Sale\Order\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class OrderZoneReservedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $eventId,
        private string $zoneId,
        private int $quantity,
    ) {
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }
    
    public function zoneId(): string
    {
        return $this->zoneId;
    }

    public function payload(): array
    {
        return [
            'eventId' => $this->eventId,
            'zoneId' => $this->zoneId,
            'quantity' => $this->quantity,
        ];
    }
}
