<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDay;
use JsonSerializable;
use Override;

class PublishedEventResponse implements JsonSerializable
{
    private array $days;

    public function __construct(
        private string $id,
        private string $name,
        private string $slug,
        private ?string $description,
        private ?string $coverImage,
        private ?string $bannerImage,
        private ?string $logo,
        private ?string $thumbnail,
        private ?string $venue,
        private ?array $coordinates,
        private string $location,
        private string $country,
        private string $city,
        private string $currency,
        private int $orderLimit,
        private array $category,
        PublishedEventDayResponse ...$days,
    ) {
        $this->days = $days;
    }

    public static function create(Event $event, Category $category): self
    {
        return new self(
            $event->id()->value(),
            $event->name()->value(),
            $event->slug()->value(),
            $event->description()->value(),
            $event->coverImage()->value(),
            $event->bannerImage()->value(),
            $event->logo()->value(),
            $event->thumbnail()->value(),
            $event->venue()->value(),
            $event->coordinates()->value(),
            $event->location()->value(),
            $event->country()->value(),
            $event->city()->value(),
            $event->currency()->value(),
            $event->orderLimit()->value(),
            $category->toChooser(),
            ...array_map(
                static fn(EventDay $day): PublishedEventDayResponse => PublishedEventDayResponse::create($day),
                $event->days(),
            ),
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
