<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use JsonSerializable;
use Override;

class PublishedEventsResponse implements JsonSerializable
{
    private array $events;

    public function __construct(private int $total, PublishedEventResponse ...$events)
    {
        $this->events = $events;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
