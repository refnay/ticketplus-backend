<?php

namespace App\Catalog\Event\Application\DeleteDay;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventDayDeleter
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(EventId $id, EventDayId $dayId, CompanyId $companyId): void
    {
        $event = $this->eventFinder->__invoke($id, $companyId);
        if ($event->findDayById($dayId) === null) {
            throw new EventDayNotFound();
        }
        $event->removeDayById($dayId);

        $this->repository->update($event);
    }
}
