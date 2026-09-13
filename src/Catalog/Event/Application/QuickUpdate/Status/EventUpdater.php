<?php

namespace App\Catalog\Event\Application\QuickUpdate\Status;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(EventId $id, EventStatus $status, CompanyId $companyId): void
    {
        $event = $this->eventFinder->__invoke($id, $companyId);

        $event->changeStatus($status);

        $this->repository->update($event);
    }
}
