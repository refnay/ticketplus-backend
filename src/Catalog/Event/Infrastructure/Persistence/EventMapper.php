<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

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
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Entity\Event as EventEntity;
use App\Shared\Infrastructure\Persistence\Entity\Day as EventDayEntity;

class EventMapper
{
    public function __construct(private RelationFetcher $fetcher) {}

    public function newEntity(Event $event): EventEntity
    {
        $entity = new EventEntity();

        $entity->setId($event->id()->toUuid());
        $entity->setName($event->name()->value());
        $entity->setSlug($event->slug()->value());
        $entity->setDescription($event->description()->value());
        $entity->setCoverImage($event->coverImage()->value());
        $entity->setBannerImage($event->bannerImage()->value());
        $entity->setLocation($event->location()->value());
        $entity->setCountry($event->country()->value());
        $entity->setCity($event->city()->value());
        $entity->setStatus($event->status()->value());
        $entity->setCompany($this->fetcher->company($event->companyId()));
        $entity->setCategory($this->fetcher->category($event->category()->id()));
        $entity->setCanvas($event->canvas()->value());

        foreach ($event->days() as $day) {
            $dayEntity = new EventDayEntity();
            $dayEntity->setId($day->id()->toUuid());
            $dayEntity->setDate($day->date()->toDateTime());
            $dayEntity->setStartTime($day->startTime()->toDateTime());
            $dayEntity->setEndTime($day->endTime()->toDateTime());
            $dayEntity->setDescription($day->description()->value());
            $dayEntity->setStatus($day->status()->value());
            $dayEntity->setEvent($entity);

            $entity->addDay($dayEntity);
        }

        return $entity;
    }

    public function newDomain(EventEntity $entity): Event
    {
        $categoryEntity = $entity->getCategory();

        $category = new Category(
            CategoryId::fromString($categoryEntity->getId()),
            CategoryName::fromString($categoryEntity->getName()),
            CategoryReference::fromInt($categoryEntity->getReference()),
            CompanyId::fromString($categoryEntity->getCompany()->getId()),
        );

        $event = new Event(
            EventId::fromString($entity->getId()),
            EventName::fromString($entity->getName()),
            EventSlug::fromString($entity->getSlug()),
            EventDescription::fromString($entity->getDescription()),
            EventCoverImage::fromString($entity->getCoverImage()),
            EventBannerImage::fromString($entity->getBannerImage()),
            EventLocation::fromString($entity->getLocation()),
            EventCountry::fromString($entity->getCountry()),
            EventCity::fromString($entity->getCity()),
            EventStatus::fromInt($entity->getStatus()),
            EventCanvas::fromArray($entity->getCanvas()),
            $category,
            CompanyId::fromString($entity->getCompany()->getId()),
        );

        foreach ($entity->getDays() as $dayEntity) {
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

        return $event;
    }

    public function update(EventEntity $entity, Event $event): void
    {
        $entity->setName($event->name()->value());
        $entity->setDescription($event->description()->value());
        $entity->setCoverImage($event->coverImage()->value());
        $entity->setBannerImage($event->bannerImage()->value());
        $entity->setLocation($event->location()->value());
        $entity->setCountry($event->country()->value());
        $entity->setCity($event->city()->value());
        $entity->setStatus($event->status()->value());
        $entity->setCategory($this->fetcher->category($event->category()->id()));
        $entity->setCanvas($event->canvas()->value());

        $currentDays = [];

        foreach ($entity->getDays() as $dayEntity) {
            $currentDays[$dayEntity->getId()->toRfc4122()] = $dayEntity;
        }

        $processedIds = [];

        foreach ($event->days() as $day) {
            $id = $day->id()->value();

            if (isset($currentDays[$id])) {
                $dayEntity = $currentDays[$id];
            } else {
                $dayEntity = new EventDayEntity();
                $dayEntity->setId($day->id()->toUuid());
                $dayEntity->setEvent($entity);

                $entity->addDay($dayEntity);
            }

            $dayEntity->setDate($day->date()->toDateTime());
            $dayEntity->setStartTime($day->startTime()->toDateTime());
            $dayEntity->setEndTime($day->endTime()->toDateTime());
            $dayEntity->setDescription($day->description()->value());
            $dayEntity->setStatus($day->status()->value());

            $processedIds[] = $id;
        }

        foreach ($entity->getDays()->toArray() as $dayEntity) {
            if (!in_array($dayEntity->getId(), $processedIds, true)) {
                $entity->removeDay($dayEntity);
            }
        }
    }

    public function entityClass(): string
    {
        return EventEntity::class;
    }
}
