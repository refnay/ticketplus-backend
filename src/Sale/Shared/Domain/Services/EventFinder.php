<?php

namespace App\Sale\Shared\Domain\Services;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Shared\Domain\Event;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\EventRepository;
use App\Sale\Shared\Domain\Exceptions\EventNotFound;

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
