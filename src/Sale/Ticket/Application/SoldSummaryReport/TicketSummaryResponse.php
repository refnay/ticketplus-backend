<?php

namespace App\Sale\Ticket\Application\SoldSummaryReport;

use JsonSerializable;
use Override;

class TicketSummaryResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $from,
        readonly private string $to,
        readonly private string $previousFrom,
        readonly private string $previousTo,
        readonly private int $quantity,
        readonly private ?float $variation,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
