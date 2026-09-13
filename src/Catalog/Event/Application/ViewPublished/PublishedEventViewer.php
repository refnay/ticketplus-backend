<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Services\EventPublishedFinder;

class PublishedEventViewer
{
    public function __construct(
        private EventPublishedFinder $eventFinder,
        private CategoryFinder $categoryFinder,
    ) {}

    public function __invoke(EventId $id): PublishedEventResponse
    {
        $event = $this->eventFinder->__invoke($id);
        $category = $this->categoryFinder->__invoke($event->categoryId(), $event->companyId());

        return PublishedEventResponse::create($event, $category);
    }
}
