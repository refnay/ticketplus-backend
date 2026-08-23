<?php

namespace App\Sale\Ticket\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class TicketCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        private array $ticketIds,
        private string $orderId,
        private string $userId,
    ) {
    }

    public function ticketIds(): array
    {
        return $this->ticketIds;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function payload(): array
    {
        return [
            'ticketIds' => $this->ticketIds,
            'orderId' => $this->orderId,
            'userId' => $this->userId,
        ];
    }
}
