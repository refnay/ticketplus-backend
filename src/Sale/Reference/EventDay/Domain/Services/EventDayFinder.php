<?php

namespace App\Sale\Reference\EventDay\Domain\Services;

use App\Sale\Reference\EventDay\Domain\EventDay;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\EventDay\Domain\EventDayRepository;
use App\Sale\Reference\EventDay\Domain\Exceptions\EventDayNotFound;

class EventDayFinder
{
    public function __construct(private EventDayRepository $repository)
    {
    }

    public function __invoke(EventDayId $id): EventDay
    {
        $day = $this->repository->findById($id);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        return $day;
    }
}
