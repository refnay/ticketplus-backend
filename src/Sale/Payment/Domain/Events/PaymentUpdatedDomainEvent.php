<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentUpdatedDomainEvent extends DomainEvent
{
    public function __construct(private string $paymentId, private string $userId, private string $token)
    {
    }

    public function paymentId(): string
    {
        return $this->paymentId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function payload(): array
    {
        return [
            'paymentId' => $this->paymentId,
            'userId' => $this->userId,
            'token' => $this->token,
        ];
    }
}
