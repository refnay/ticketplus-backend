<?php

namespace App\Catalog\Seat\Infrastructure\Persistence;

use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Zone\Domain\ZoneId;
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
        $entity->setZone($this->fetcher->zone($seat->zoneId()));

        return $entity;
    }

    public function newDomain(SeatEntity $entity): Seat
    {

        $seat = new Seat(
            SeatId::fromString($entity->getId()),
            SeatCode::fromString($entity->getCode()),
            SeatStatus::fromInt($entity->getStatus()),
            ZoneId::fromString($entity->getZone()->getId()),
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
