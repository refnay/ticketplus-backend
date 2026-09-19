<?php

namespace App\Catalog\Event\Application\Choose;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;

class EventChooser
{
    public function __construct(private EventRepository $repository) {}

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): EventChoicesResponse {
        $events = $this->repository->searchByFilters(
            $filters,
            $orderBy,
            $order,
            $limit,
            $offset,
        );

        return new EventChoicesResponse(
            ...array_map(
                static fn(Event $event): array => $event->toChooser(),
                $events,
            ),
        );
    }
}
