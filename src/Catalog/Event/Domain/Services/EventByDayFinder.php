<?php

namespace App\Catalog\Event\Domain\Services;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Shared\Domain\CompanyId;

class EventByDayFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventDayId $dayId, CompanyId $companyId): Event
    {
        $event = $this->repository->findByDayId($dayId, $companyId);

        if (is_null($event)) {
            throw new EventDayNotFound();
        }

        return $event;
    }
}
