<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Zone\Domain\Zone;
use JsonSerializable;
use Override;

class ZoneResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private int $hierarchy,
        readonly private int $available,
        readonly private array $quantity,
        readonly private float $price,
        readonly private bool $numberedSeating,
    ) {}

    public static function create(Zone $zone): self
    {
        $quantity = $zone->quantity();

        return new self(
            $zone->id()->value(),
            $zone->name()->value(),
            $zone->hierarchy()->value(),
            max(0, $quantity->total() - $quantity->sold() - $quantity->reserved()),
            $quantity->toArray(),
            $zone->price()->value(),
            $zone->numberedSeating()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
