<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Event\Domain\Event;
use JsonSerializable;
use Override;

class EventResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private string $slug,
        readonly private ?string $thumbnail,
        readonly private ?string $venue,
        readonly private string $location,
        readonly private string $country,
        readonly private string $city,
        readonly private string $currency,
        readonly private array $category,
        readonly private ?string $date,
    ) {}

    public static function create(Event $event, Category $category): self
    {
        return new self(
            $event->id()->value(),
            $event->name()->value(),
            $event->slug()->value(),
            $event->thumbnail()->value(),
            $event->venue()->value(),
            $event->location()->value(),
            $event->country()->value(),
            $event->city()->value(),
            $event->currency()->value(),
            $category->toChooser(),
            $event->firstDay()?->date()->format(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
