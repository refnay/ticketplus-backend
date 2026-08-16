<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentApprovedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $zoneId,
        private string $dayId,
        private int $quantity,
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

    public function payload(): array
    {
        return [
            'zoneId' => $this->zoneId,
            'dayId' => $this->dayId,
            'quantity' => $this->quantity,
        ];
    }
}