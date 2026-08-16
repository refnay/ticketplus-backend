<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentProcessedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $eventId,
        private string $zoneId,
        private string $dayId,
        private int $quantity,
        private int $status,
        private ?array $seats,
    ) {
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

    public function status(): int
    {
        return $this->status;
    }

    public function seats(): ?array
    {
        return $this->seats;
    }

    public function payload(): array
    {
        return [
            'eventId' => $this->eventId,
            'zoneId' => $this->zoneId,
            'dayId' => $this->dayId,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'seats' => $this->seats,
        ];
    }
}