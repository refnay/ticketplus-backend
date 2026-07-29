<?php

namespace App\Catalog\Event\Application\Find;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDay;
use JsonSerializable;
use Override;

class EventResponse implements JsonSerializable
{
    private array $days = [];

    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private string $slug,
        readonly private ?string $description,
        readonly private string $coverImage,
        readonly private string $bannerImage,
        readonly private string $location,
        readonly private string $country,
        readonly private string $city,
        readonly private int $status,
        readonly private array $category,
        EventDayResponse ...$days,
    ) {
        $this->days = $days;
    }

    public static function create(Event $event): self
    {
        return new self(
            $event->id()->value(),
            $event->name()->value(),
            $event->slug()->value(),
            $event->description()->value(),
            $event->coverImage()->value(),
            $event->bannerImage()->value(),
            $event->location()->value(),
            $event->country()->value(),
            $event->city()->value(),
            $event->status()->value(),
            $event->category()->toChooser(),
            ...array_map(self::dayResponse(...), $event->days()),
        );
    }

    private static function dayResponse(EventDay $day): EventDayResponse
    {
        return EventDayResponse::create($day);
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
