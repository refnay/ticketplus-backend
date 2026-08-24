<?php

namespace App\Sale\EventDay\Domain\Services;

use App\Sale\EventDay\Domain\EventDay;
use App\Sale\EventDay\Domain\EventDayId;
use App\Sale\EventDay\Domain\EventDayRepository;
use App\Sale\Event\Domain\EventId;
use App\Sale\EventDay\Domain\Exceptions\EventDayNotFound;

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
