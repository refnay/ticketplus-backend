<?php

namespace App\Sale\Order\Application\ReportApprovedSalesEvolution;

use JsonSerializable;
use Override;

class OrderEvolutionResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $from,
        readonly private string $to,
        readonly private string $currency,
        readonly private string $interval,
        readonly private array $sales,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
