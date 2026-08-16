<?php

namespace App\Sale\Order\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class OrderReservedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $eventId,
        private string $dayId,
        private string $zoneId,
        private int $quantity,
        private ?array $seatIds,
    ) {
    }

    public function seatIds(): ?array
    {
        return $this->seatIds;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function zoneId(): string
    {
        return $this->zoneId;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }
    
    public function quantity(): int
    {
        return $this->quantity;
    }

    public function payload(): array
    {
        return [
            'eventId' => $this->eventId,
            'seatIds' => $this->seatIds,
            'zoneId' => $this->zoneId,
            'dayId' => $this->dayId,
            'quantity' => $this->quantity,
        ];
    }
}
