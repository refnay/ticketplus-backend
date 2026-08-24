<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\Sale\Order\Domain\Exceptions\OrderNotFound;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Reference\Zone\Domain\Exceptions\ZoneNotFound;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Shared\Infrastructure\Persistence\Entity\Zone as ZoneEntity;
use App\Shared\Infrastructure\Persistence\Entity\Seat as SeatEntity;
use App\Shared\Infrastructure\Persistence\Entity\Purchase as OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    
    public function zone(ZoneId $id): ZoneEntity
    {
        try {
            return $this->entityManager->getReference(ZoneEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new ZoneNotFound();
        }
    }

    public function seat(SeatId $id): SeatEntity
    {
        try {
            return $this->entityManager->getReference(SeatEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new SeatNotFound();
        }
    }

    public function order(OrderId $id): OrderEntity
    {
        try {
            return $this->entityManager->getReference(OrderEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new OrderNotFound();
        }
    }
}
