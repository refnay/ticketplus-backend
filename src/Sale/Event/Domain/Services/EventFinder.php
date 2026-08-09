<?php

namespace App\Sale\Event\Domain\Services;

use App\Sale\Event\Domain\Event;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\EventRepository;
use App\Sale\Event\Domain\Exceptions\EventNotFound;
use App\Sale\Purchase\Domain\CompanyId;

class EventFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventId $id, CompanyId $companyId): Event
    {
        $event = $this->repository->findById($id, $companyId);

        if (is_null($event)) {
            throw new EventNotFound();
        }

        return $event;
    }
}