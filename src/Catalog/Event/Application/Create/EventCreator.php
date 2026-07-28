<?php

namespace App\Catalog\Event\Application\Create;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventSlug;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Event\Domain\Exceptions\EventAlreadyExists;
use App\Catalog\Event\Domain\Exceptions\EventNotFound;
use App\Catalog\Event\Domain\Services\EventBySlugFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Domain\Services\SlugGenerator;

class EventCreator
{
    public function __construct(
        private EventRepository $repository,
        private SlugGenerator $slugGenerator,
        private CategoryFinder $categoryFinder,
        private EventBySlugFinder $eventFinder,
    ) {
    }

    public function __invoke(
        EventName $name,
        EventDescription $description,
        EventLocation $location,
        EventCountry $country,
        EventCity $city,
        EventStatus $status,
        CategoryId $categoryId, 
        CompanyId $companyId
    ): string {
        $category = $this->categoryFinder->__invoke($categoryId, $companyId);
        $slug = EventSlug::fromString($this->slugGenerator->generate($name->value()));

        try {
            $this->eventFinder->__invoke($slug, $companyId);
            throw new EventAlreadyExists();
        } catch (EventNotFound) {
        }
        
        $event = Event::create(
            $name,
            $slug,
            $description,
            $location,
            $country,
            $city,
            $status,
            $category,
            $companyId,
        );

        $this->repository->save($event);

        return $event->id()->value();
    }
}
