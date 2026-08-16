<?php

namespace App\Sale\Payment\Domain\Provider;

use JsonSerializable;
use Override;

class ProviderResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $status,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function status(): string
    {
        return $this->status;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
