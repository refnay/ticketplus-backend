<?php

namespace App\Catalog\Event\Application\Find;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Services\EventFinder as ServicesEventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventFinder
{
    public function __construct(private ServicesEventFinder $finder)
    {
    }

    public function __invoke(EventId $id, CompanyId $companyId): EventResponse
    {
        $event = $this->finder->__invoke($id, $companyId);

        return EventResponse::create($event);
    }
}
