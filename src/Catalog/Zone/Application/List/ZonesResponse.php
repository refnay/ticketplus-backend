<?php

namespace App\Catalog\Zone\Application\List;

use JsonSerializable;
use Override;

class ZonesResponse implements JsonSerializable 
{
    private array $zones = [];

    public function __construct(private int $total, ZoneResponse ...$zones)
    {
        $this->zones = $zones;
    }

    public function zones(): array
    {
        return $this->zones;
    }

    public function total(): int
    {
        return $this->total;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this); 
    }
}