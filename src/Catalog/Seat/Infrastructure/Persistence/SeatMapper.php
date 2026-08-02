<?php

namespace App\Catalog\Seat\Infrastructure\Persistence;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventBannerImage;
use App\Catalog\Event\Domain\EventCanvas;
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
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneCanvas;
use App\Catalog\Zone\Domain\ZoneCurrency;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneTaxRate;
use App\Shared\Infrastructure\Persistence\Entity\Seat as SeatEntity;

class SeatMapper
{
    public function __construct(private RelationFetcher $fetcher) {}

    public function newEntity(Seat $seat): SeatEntity
    {
        $entity = new SeatEntity();

        $entity->setId($seat->id()->toUuid());
        $entity->setCode($seat->code()->value());
        $entity->setStatus($seat->status()->value());
        $entity->setZone($this->fetcher->zone($seat->zone()->id()));

        return $entity;
    }

    public function newDomain(SeatEntity $entity): Seat
    {
        $zoneEntity = $entity->getZone();
        $dayEntity = $zoneEntity->getDay();
        $eventEntity = $dayEntity->getEvent();
        $categoryEntity = $eventEntity->getCategory();

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
            EventCanvas::fromArray($eventEntity->getCanvas()),
            $category,
            CompanyId::fromString($eventEntity->getCompany()->getId()),
        );

        foreach ($eventEntity->getDays() as $dayEntity) {
            $event->addDay(new EventDay(
                EventDayId::fromString($dayEntity->getId()),
                EventDayDate::fromDate($dayEntity->getDate()),
                EventDayStartTime::fromDateTime($dayEntity->getStartTime()),
                EventDayEndTime::fromDateTime($dayEntity->getEndTime()),
                EventDayDescription::fromString($dayEntity->getDescription()),
                EventDayStatus::fromInt($dayEntity->getStatus()),
                $event,
            ));
        }

        $day = $event->findDayById(EventDayId::fromString($zoneEntity->getDay()->getId()));

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = new Zone(
            ZoneId::fromString($zoneEntity->getId()),
            ZoneName::fromString($zoneEntity->getName()),
            ZoneCurrency::fromString($zoneEntity->getCurrency()),
            ZoneHierarchy::fromInt($zoneEntity->getHierarchy()),
            ZoneNumberedSeating::fromBool($zoneEntity->isNumberedSeating()),
            ZonePrice::fromFloat($zoneEntity->getPrice()),
            ZoneQuantity::create(
                $zoneEntity->getTotalQuantity(),
                $zoneEntity->getSoldQuantity(),
            ),
            ZoneTaxRate::fromFloat($zoneEntity->getTaxRate()),
            ZoneCanvas::fromArray($zoneEntity->getCanvas()),
            $day,
        );

        $seat = new Seat(
            SeatId::fromString($entity->getId()),
            SeatCode::fromString($entity->getCode()),
            SeatStatus::fromInt($entity->getStatus()),
            $zone,
        );

        return $seat;
    }

    public function update(SeatEntity $entity, Seat $seat): void
    {
        $entity->setCode($seat->code()->value());
        $entity->setStatus($seat->status()->value());
    }

    public function entityClass(): string
    {
        return SeatEntity::class;
    }
}
