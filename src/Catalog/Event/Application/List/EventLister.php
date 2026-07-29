<?php

namespace App\Catalog\Event\Application\List;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;

class EventLister
{
    public function __construct(private EventRepository $repository)
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
        return fn(Event $event) => EventResponse::create($event);
    }
}