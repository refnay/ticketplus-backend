<?php

namespace App\Catalog\Event\Domain\Services;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventNotFound;
use App\Catalog\Shared\Domain\CompanyId;

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