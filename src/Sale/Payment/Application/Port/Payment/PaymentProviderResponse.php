<?php

namespace App\Sale\Payment\Application\Port\Payment;

use JsonSerializable;
use Override;

class PaymentProviderResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $provider,
        readonly private int $status,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }
    
    public function provider(): string
    {
        return $this->provider;
    }

    public function status(): int
    {
        return $this->status;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
