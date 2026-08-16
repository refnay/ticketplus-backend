<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentWithEventProcessedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $zoneId,
        private string $dayId,
        private int $quantity,
        private int $status,
        private ?array $seatIds,
    ) {
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

    public function seatIds(): ?array
    {
        return $this->seatIds;
    }

    public function payload(): array
    {
        return [
            'zoneId' => $this->zoneId,
            'dayId' => $this->dayId,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'seatIds' => $this->seatIds,
        ];
    }
}