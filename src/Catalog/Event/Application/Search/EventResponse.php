<?php

namespace App\Catalog\Event\Application\Search;

use App\Catalog\Event\Domain\Event;
use JsonSerializable;
use Override;

class EventResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private string $location,
        readonly private string $country,
        readonly private string $city,
        readonly private int $status,
        readonly private string $category,
        readonly private string $date,
    ) {
    }

    public static function create(Event $event): self
    {
        return new self(
            $event->id()->value(),
            $event->name()->value(),
            $event->location()->value(),
            $event->country()->value(),
            $event->city()->value(),
            $event->status()->value(),
            $event->category()->name(),
            $event->firstDay()?->date()->asDMY(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
