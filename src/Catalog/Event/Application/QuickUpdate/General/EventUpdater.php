<?php

namespace App\Catalog\Event\Application\QuickUpdate\General;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(
        private EventRepository $repository,
        private CategoryFinder $categoryFinder,
        private EventFinder $eventFinder,
    ) {}

    public function __invoke(
        EventId $id,
        EventName $name,
        EventDescription $description,
        CategoryId $categoryId,
        CompanyId $companyId,
    ): void {
        $event = $this->eventFinder->__invoke($id, $companyId);
        $category = $this->categoryFinder->__invoke($categoryId, $companyId);

        $event->changeName($name);
        $event->changeDescription($description);
        $event->changeCategoryId($category->id());

        $this->repository->update($event);
    }
}
