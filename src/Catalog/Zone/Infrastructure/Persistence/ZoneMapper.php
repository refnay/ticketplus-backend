<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Event\Domain\EventDayId;
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
use App\Shared\Infrastructure\Persistence\Entity\Zone as ZoneEntity;

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
        $entity->setDay($this->fetcher->day($zone->dayId()));

        return $entity;
    }

    public function newDomain(ZoneEntity $entity): Zone
    {
        $zone = new Zone(
            ZoneId::fromString($entity->getId()),
            ZoneName::fromString($entity->getName()),
            ZoneCurrency::fromString($entity->getCurrency()),
            ZoneHierarchy::fromInt($entity->getHierarchy()),
            ZoneNumberedSeating::fromBool($entity->isNumberedSeating()),
            ZonePrice::fromFloat($entity->getPrice()),
            ZoneQuantity::create($entity->getTotalQuantity(), $entity->getSoldQuantity()),
            ZoneTaxRate::fromFloat($entity->getTaxRate()),
            ZoneCanvas::fromArray($entity->getCanvas()),
            EventDayId::fromString($entity->getDay()->getId()),
        );

        return $zone;
    }

    public function update(ZoneEntity $entity, Zone $zone): void
    {
        $entity->setName($zone->name()->value());
        $entity->setTotalQuantity($zone->quantity()->total());
        $entity->setSoldQuantity($zone->quantity()->sold());
        $entity->setPrice($zone->price()->value());
        $entity->setHierarchy($zone->hierarchy()->value());
        $entity->setTaxRate($zone->taxRate()->value());
        $entity->setCurrency($zone->currency()->value());
        $entity->setNumberedSeating($zone->numberedSeating()->value());
    }

    public function entityClass(): string
    {
        return ZoneEntity::class;
    }
}
