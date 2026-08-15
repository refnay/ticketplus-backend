<?php

namespace App\Sale\Order\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class OrderSeatsReservedDomainEvent extends DomainEvent
{
    public function __construct(
        private array $seatIds,
        private string $zoneId,
    ) {
    }

    public function seatIds(): array
    {
        return $this->seatIds;
    }

    public function zoneId(): string
    {
        return $this->zoneId;
    }

    public function payload(): array
    {
        return [
            'seatIds' => $this->seatIds,
            'zoneId' => $this->zoneId,
        ];
    }
}
