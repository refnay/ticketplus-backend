<?php

namespace App\Sale\Payment\Application\Port\Gateway;

use JsonSerializable;
use Override;

class TransactionResult implements JsonSerializable
{
    public function __construct(
        readonly private string $transactionId,
        readonly private string $gateway,
        readonly private int $status,
    ) {
    }

    public function transactionId(): string
    {
        return $this->transactionId;
    }

    public function gateway(): string
    {
        return $this->gateway;
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
