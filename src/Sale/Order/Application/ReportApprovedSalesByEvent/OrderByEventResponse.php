<?php

namespace App\Sale\Order\Application\ReportApprovedSalesByEvent;

use JsonSerializable;
use Override;

class OrderByEventResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $from,
        readonly private string $to,
        readonly private string $currency,
        readonly private array $events,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
