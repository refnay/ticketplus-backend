<?php

namespace App\Catalog\Event\Application\Search;

use JsonSerializable;
use Override;

class EventsResponse implements JsonSerializable 
{
    private array $events = [];

    public function __construct(private int $total, EventResponse ...$events)
    {
        $this->events = $events;
    }

    public function events(): array
    {
        return $this->events;
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