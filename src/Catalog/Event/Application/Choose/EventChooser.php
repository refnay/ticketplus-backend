<?php

namespace App\Catalog\Event\Application\Choose;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;

class EventChooser
{
    public function __construct(private EventRepository $repository) {}

    public function __invoke(string $companyId): EventChoicesResponse
    {
        $events = $this->repository->searchByFilters(
            ['company' => $companyId],
            'name',
            'ASC',
            null,
            null,
        );

        return new EventChoicesResponse(
            ...array_map(
                static fn(Event $event): array => $event->toChooser(),
                $events,
            ),
        );
    }
}
