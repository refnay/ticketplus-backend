<?php

namespace App\Sale\Order\Application\PaidSummaryReport;

use JsonSerializable;
use Override;

class OrderSummaryResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $from,
        readonly private string $to,
        readonly private string $previousFrom,
        readonly private string $previousTo,
        readonly private float $amount,
        readonly private string $currency,
        readonly private ?float $variationPercentage,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
