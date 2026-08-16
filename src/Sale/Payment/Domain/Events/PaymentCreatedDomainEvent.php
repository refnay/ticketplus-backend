<?php

namespace App\Sale\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class PaymentCreatedDomainEvent extends DomainEvent
{
    public function __construct(private string $id, private int $method)
    {
    }

    public function method(): int
    {
        return $this->method;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
        ];
    }
}