<?php

namespace App\Sale\Ticket\Application\ReportValidationSummary;

use JsonSerializable;
use Override;

class TicketValidationSummaryResponse implements JsonSerializable
{
    public function __construct(
        readonly private int $total,
        readonly private int $validated,
        readonly private int $pending,
        readonly private float $validatedPercentage,
    ) {}

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
