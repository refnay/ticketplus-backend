<?php

namespace App\Sale\Order\Application\ApprovedSalesSummaryReport;

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
        readonly private ?float $variation,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
