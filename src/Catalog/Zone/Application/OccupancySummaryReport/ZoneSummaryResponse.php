<?php

namespace App\Catalog\Zone\Application\OccupancySummaryReport;

use JsonSerializable;
use Override;

class ZoneSummaryResponse implements JsonSerializable
{
    public function __construct(
        readonly private int $total,
        readonly private int $sold,
        readonly private int $reserved,
        readonly private int $available,
        readonly private float $occupancy,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
