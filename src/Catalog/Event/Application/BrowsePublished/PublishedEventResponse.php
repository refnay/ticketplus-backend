<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Event\Domain\Event;
use JsonSerializable;
use Override;

class PublishedEventResponse implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private string $slug,
        private ?string $thumbnail,
        private ?string $venue,
        private string $location,
        private string $country,
        private string $city,
        private string $currency,
        private array $category,
        private ?string $date,
    ) {
    }

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
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
