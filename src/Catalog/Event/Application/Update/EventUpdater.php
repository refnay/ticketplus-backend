<?php

namespace App\Catalog\Event\Application\Update;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(
        private EventRepository $repository,
        private CategoryFinder $categoryFinder,
        private EventFinder $eventFinder,
        private EventSynchronizer $synchronizer,
    ) {}

    public function __invoke(
        EventId $id,
        EventName $name,
        EventDescription $description,
        EventLocation $location,
        EventCountry $country,
        EventCity $city,
        EventStatus $status,
        CategoryId $categoryId,
        CompanyId $companyId,
        array $days,
    ): void {
        $event = $this->eventFinder->__invoke($id, $companyId);
        $category = $this->categoryFinder->__invoke($categoryId, $companyId);

        $event->changeName($name);
        $event->changeDescription($description);
        $event->changeLocation($location);
        $event->changeCountry($country);
        $event->changeCity($city);
        $event->changeStatus($status);
        $event->changeCategory($category);

        $this->synchronizer->days($event, $days);

        $this->repository->save($event);
    }
}
