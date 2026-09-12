<?php

namespace App\Catalog\Event\Domain\Services;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;

class PublishedEventByDayFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventDayId $dayId): Event
    {
        $event = $this->repository->findPublishedByDayId($dayId);

        if (is_null($event)) {
            throw new EventDayNotFound();
        }

        return $event;
    }
}
