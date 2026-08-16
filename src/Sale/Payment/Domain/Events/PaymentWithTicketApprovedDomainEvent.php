<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentWithTicketApprovedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $orderId,
        private string $userId,
    ) {
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
            'orderId' => $this->orderId,
            'userId' => $this->userId,
        ];
    }
}