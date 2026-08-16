<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentCreatedDomainEvent extends DomainEvent
{
    public function __construct(private string $orderId, private int $paymentMethod, private string $userId)
    {
    }

    public function paymentMethod(): int
    {
        return $this->paymentMethod;
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
            'paymentMethod' => $this->paymentMethod,
            'userId' => $this->userId,
        ];
    }
}