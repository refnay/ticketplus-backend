<?php

namespace App\Catalog\Seat\Application\List;

use JsonSerializable;
use Override;

class SeatsResponse implements JsonSerializable 
{
    private array $seats = [];

    public function __construct(private int $total, SeatResponse ...$seats)
    {
        $this->seats = $seats;
    }

    public function seats(): array
    {
        return $this->seats;
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