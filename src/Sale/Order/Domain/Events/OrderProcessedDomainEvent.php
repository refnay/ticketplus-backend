<?php

namespace App\Sale\Order\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class OrderProcessedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $eventId,
        private string $dayId,
        private array $items,
        private int $status,
    ) {
    }

    public function items(): array
    {
        return $this->items;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function payload(): array
    {
        return [
            'eventId' => $this->eventId,
            'dayId' => $this->dayId,
            'items' => $this->items,
            'status' => $this->status,
        ];
    }
}
