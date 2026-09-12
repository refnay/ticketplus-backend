<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use JsonSerializable;
use Override;

class AvailableSeatsResponse implements JsonSerializable
{
    private array $seats;

    public function __construct(private int $total, AvailableSeatResponse ...$seats)
    {
        $this->seats = $seats;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
