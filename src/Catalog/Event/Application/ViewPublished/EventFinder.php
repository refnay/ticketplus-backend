<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Services\EventPublishedFinder;

class EventFinder
{
    public function __construct(
        private EventPublishedFinder $eventFinder,
        private CategoryFinder $categoryFinder,
    ) {}

    public function __invoke(EventId $id): EventResponse
    {
        $event = $this->eventFinder->__invoke($id);
        $category = $this->categoryFinder->__invoke($event->categoryId(), $event->companyId());

        return EventResponse::create($event, $category);
    }
}
