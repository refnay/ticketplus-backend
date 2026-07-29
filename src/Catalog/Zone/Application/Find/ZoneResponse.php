<?php

namespace App\Catalog\Zone\Application\Find;

use App\Catalog\Zone\Domain\Zone;
use JsonSerializable;
use Override;

class ZoneResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private string $currency,
        readonly private int $hierarchy,
        readonly private float $price,
        readonly private float $taxRate,
        readonly private float $total,
        readonly private array $quantity,
    ) {
    }

    public static function create(Zone $zone): self
    {
        return new self(
            $zone->id()->value(),
            $zone->name()->value(),
            $zone->currency()->value(),
            $zone->hierarchy()->value(),
            $zone->price()->value(),
            $zone->taxRate()->value(),
            $zone->total(),
            $zone->quantity()->toArray(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
