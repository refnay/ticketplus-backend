<?php

namespace App\Catalog\Seat\Application\Find;

use App\Catalog\Seat\Domain\Seat;
use JsonSerializable;
use Override;

class SeatResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $code,
        readonly private int $status,
    ) {
    }

    public static function create(Seat $seat): self
    {
        return new self(
            $seat->id()->value(),
            $seat->code()->value(),
            $seat->status()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
