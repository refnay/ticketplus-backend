<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventBannerImage;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventCoverImage;
use App\Catalog\Event\Domain\EventDay;
use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventDayStatus;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventSlug;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneCurrency;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneTaxRate;
use App\Shared\Infrastructure\Persistence\Entity\Zone as ZoneEntity;
use App\Shared\Infrastructure\Persistence\Entity\Day as EventDayEntity;

class ZoneMapper
{
    public function __construct(private RelationFetcher $fetcher) {}

    public function newEntity(Zone $zone): ZoneEntity
    {
        $entity = new ZoneEntity();

        $entity->setId($zone->id()->toUuid());
        $entity->setName($zone->name()->value());
        $entity->setTotalQuantity($zone->quantity()->total());
        $entity->setSoldQuantity($zone->quantity()->sold());
        $entity->setPrice($zone->price()->value());
        $entity->setHierarchy($zone->hierarchy()->value());
        $entity->setTaxRate($zone->taxRate()->value());
        $entity->setCurrency($zone->currency()->value());
        $entity->setNumberedSeating($zone->numberedSeating()->value());
        $entity->setDay($this->fetcher->day($zone->day()->id()));

        return $entity;
    }

    public function newDomain(ZoneEntity $entity): Zone
    {
        $categoryEntity = $entity->getDay()->getEvent()->getCategory();
        $eventEntity = $entity->getDay()->getEvent();

        $category = new Category(
            CategoryId::fromString($categoryEntity->getId()),
            CategoryName::fromString($categoryEntity->getName()),
            CategoryReference::fromInt($categoryEntity->getReference()),
            CompanyId::fromString($categoryEntity->getCompany()->getId()),
        );

        $event = new Event(
            EventId::fromString($eventEntity->getId()),
            EventName::fromString($eventEntity->getName()),
            EventSlug::fromString($eventEntity->getSlug()),
            EventDescription::fromString($eventEntity->getDescription()),
            EventCoverImage::fromString($eventEntity->getCoverImage()),
            EventBannerImage::fromString($eventEntity->getBannerImage()),
            EventLocation::fromString($eventEntity->getLocation()),
            EventCountry::fromString($eventEntity->getCountry()),
            EventCity::fromString($eventEntity->getCity()),
            EventStatus::fromInt($eventEntity->getStatus()),
            $category,
            CompanyId::fromString($eventEntity->getCompany()->getId()),
        );

        foreach ($eventEntity->getDays() as $dayEntity) {
            $day = new EventDay(
                EventDayId::fromString($dayEntity->getId()),
                EventDayDate::fromDate($dayEntity->getDate()),
                EventDayStartTime::fromDateTime($dayEntity->getStartTime()),
                EventDayEndTime::fromDateTime($dayEntity->getEndTime()),
                EventDayDescription::fromString($dayEntity->getDescription()),
                EventDayStatus::fromInt($dayEntity->getStatus()),
                $event,
            );

            $event->addDay($day);
        }

        $day = $event->findDayById(EventDayId::fromString($entity->getDay()->getId()));

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = new Zone(
            ZoneId::fromString($entity->getId()),
            ZoneName::fromString($entity->getName()),
            ZoneCurrency::fromString($entity->getCurrency()),
            ZoneHierarchy::fromInt($entity->getHierarchy()),
            ZoneNumberedSeating::fromBool($entity->isNumberedSeating()),
            ZonePrice::fromFloat($entity->getPrice()),
            ZoneQuantity::create($entity->getTotalQuantity(), $entity->getSoldQuantity()),
            ZoneTaxRate::fromFloat($entity->getTaxRate()),
            $day,
        );

        return $zone;
    }

    public function update(ZoneEntity $entity, Zone $zone): void
    {
        $entity->setId($zone->id()->toUuid());
        $entity->setName($zone->name()->value());
        $entity->setTotalQuantity($zone->quantity()->total());
        $entity->setSoldQuantity($zone->quantity()->sold());
        $entity->setPrice($zone->price()->value());
        $entity->setHierarchy($zone->hierarchy()->value());
        $entity->setTaxRate($zone->taxRate()->value());
        $entity->setCurrency($zone->currency()->value());
        $entity->setNumberedSeating($zone->numberedSeating()->value());
        $entity->setDay($this->fetcher->day($zone->day()->id()));
    }

    public function entityClass(): string
    {
        return ZoneEntity::class;
    }
}
