<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Seat\Domain\Seat;
use JsonSerializable;
use Override;

class AvailableSeatResponse implements JsonSerializable
{
    public function __construct(private string $id, private string $code)
    {
    }

    public static function create(Seat $seat): self
    {
        return new self($seat->id()->value(), $seat->code()->value());
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
