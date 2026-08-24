<?php

namespace App\Sale\Reference\Event\Domain\Services;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Reference\Event\Domain\Event;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Event\Domain\EventRepository;
use App\Sale\Reference\Event\Domain\Exceptions\EventNotFound;

class EventFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventId $id, ?CompanyId $companyId = null): Event
    {
        $event = $this->repository->findById($id, $companyId);

        if (is_null($event)) {
            throw new EventNotFound();
        }

        return $event;
    }
}
