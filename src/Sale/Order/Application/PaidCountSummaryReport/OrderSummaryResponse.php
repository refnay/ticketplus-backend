<?php

namespace App\Sale\Order\Application\PaidCountSummaryReport;

use JsonSerializable;
use Override;

class OrderSummaryResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $from,
        readonly private string $to,
        readonly private int $quantity,
        readonly private float $averageAmount,
        readonly private string $currency,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
