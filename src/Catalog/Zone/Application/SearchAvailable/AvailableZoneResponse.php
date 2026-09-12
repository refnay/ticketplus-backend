<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Zone\Domain\Zone;
use JsonSerializable;
use Override;

class AvailableZoneResponse implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private int $hierarchy,
        private int $available,
        private float $price,
        private bool $numberedSeating,
    ) {
    }

    public static function create(Zone $zone): self
    {
        $quantity = $zone->quantity();

        return new self(
            $zone->id()->value(),
            $zone->name()->value(),
            $zone->hierarchy()->value(),
            max(0, $quantity->total() - $quantity->sold() - $quantity->reserved()),
            $zone->price()->value(),
            $zone->numberedSeating()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
