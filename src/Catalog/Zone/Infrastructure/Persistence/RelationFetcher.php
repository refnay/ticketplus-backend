<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Shared\Infrastructure\Persistence\Entity\Day as DayEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function day(EventDayId $id): DayEntity
    {
        try {
            return $this->entityManager->getReference(DayEntity::class, $id->value());
        } catch (Throwable) {
            throw new EventDayNotFound();
        }
    }
}