<?php

namespace App\Sale\Ticket\Application\Search;

use JsonSerializable;
use Override;

class TicketsResponse implements JsonSerializable
{
    private array $tickets = [];

    public function __construct(private int $total, TicketResponse ...$tickets)
    {
        $this->tickets = $tickets;
    }

    public function tickets(): array
    {
        return $this->tickets;
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
