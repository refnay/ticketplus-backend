<?php

namespace App\Catalog\Event\Application\Find;

use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Services\EventFinder as ServicesEventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventFinder
{
    public function __construct(private ServicesEventFinder $eventfinder, private CategoryFinder $categoryFinder)
    {
    }

    public function __invoke(EventId $id, CompanyId $companyId): EventResponse
    {
        $event = $this->eventfinder->__invoke($id, $companyId);
        $category = $this->categoryFinder->__invoke($event->categoryId(), $companyId);

        return EventResponse::create($event, $category);
    }
}
