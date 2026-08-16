<?php

namespace App\Sale\Shared\Domain\Services;

use App\Sale\Shared\Domain\EventDay;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventDayRepository;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\EventDayNotFound;

class EventDayFinder
{
    public function __construct(private EventDayRepository $repository)
    {
    }

    public function __invoke(EventId $eventId, EventDayId $id): EventDay
    {
        $day = $this->repository->findById($eventId, $id);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        return $day;
    }
}