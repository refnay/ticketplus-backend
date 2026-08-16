<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentWithOrderProcessedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $orderId,
        private string $userId,
        private int $status,
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

    public function status(): int
    {
        return $this->status;
    }

    public function payload(): array
    {
        return [
            'orderId' => $this->orderId,
            'userId' => $this->userId,
            'status' => $this->status,
        ];
    }
}