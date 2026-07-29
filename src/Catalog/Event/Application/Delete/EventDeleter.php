<?php

namespace App\Catalog\Event\Application\Delete;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Services\EventFinder as ServicesEventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventDeleter
{
    public function __construct(private ServicesEventFinder $finder, private EventRepository $repository)
    {
    }

    public function __invoke(EventId $id, CompanyId $companyId): void
    {
        $event = $this->finder->__invoke($id, $companyId);
        
        $this->repository->delete($event);
    }
}
