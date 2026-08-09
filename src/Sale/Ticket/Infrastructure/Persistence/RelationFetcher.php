<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\Sale\Purchase\Domain\Exceptions\PurchaseNotFound;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Purchase\Domain\SeatId;
use App\Sale\Purchase\Domain\ZoneId;
use App\Sale\Shared\Domain\Exceptions\SeatNotFound;
use App\Sale\Shared\Domain\Exceptions\ZoneNotFound;
use App\Shared\Infrastructure\Persistence\Entity\Zone as ZoneEntity;
use App\Shared\Infrastructure\Persistence\Entity\Seat as SeatEntity;
use App\Shared\Infrastructure\Persistence\Entity\Purchase as PurchaseEntity;
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

    public function purchase(PurchaseId $id): PurchaseEntity
    {
        try {
            return $this->entityManager->getReference(PurchaseEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new PurchaseNotFound();
        }
    }
}