<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use JsonSerializable;
use Override;

class AvailableZonesResponse implements JsonSerializable
{
    private array $zones;

    public function __construct(private int $total, AvailableZoneResponse ...$zones)
    {
        $this->zones = $zones;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
