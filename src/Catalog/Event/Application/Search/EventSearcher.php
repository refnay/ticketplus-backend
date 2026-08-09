<?php

namespace App\Catalog\Event\Application\Search;

use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;

class EventSearcher
{
    public function __construct(private EventRepository $repository, private CategoryFinder $categoryFinder)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): EventsResponse {
        $events = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new EventsResponse($total, ...array_map($this->makeResponse(), $events));
    }

    private function makeResponse(): callable
    {
        return function (Event $event) {
            $category = $this->categoryFinder->__invoke($event->categoryId(), $event->companyId());

            return EventResponse::create($event, $category);
        };
    }
}