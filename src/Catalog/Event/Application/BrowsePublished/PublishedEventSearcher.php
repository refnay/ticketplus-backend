<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;

class PublishedEventSearcher
{
    public function __construct(
        private EventRepository $repository,
        private CategoryFinder $categoryFinder,
    ) {
    }

    public function __invoke(BrowsePublishedEventsQuery $query): PublishedEventsResponse
    {
        $events = $this->repository->searchByFilters(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
        $total = $this->repository->countByFilters($query->filters());

        return new PublishedEventsResponse($total, ...array_map($this->makeResponse(), $events));
    }

    private function makeResponse(): callable
    {
        return function (Event $event): PublishedEventResponse {
            $category = $this->categoryFinder->__invoke($event->categoryId(), $event->companyId());

            return PublishedEventResponse::create($event, $category);
        };
    }
}
