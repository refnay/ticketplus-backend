<?php

namespace App\Catalog\Seat\Infrastructure\Persistence;

use App\Catalog\Zone\Domain\Exceptions\ZoneNotFound;
use App\Catalog\Zone\Domain\ZoneId;
use App\Shared\Infrastructure\Persistence\Entity\Zone as ZoneEntity;
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
            return $this->entityManager->getReference(ZoneEntity::class, $id->value());
        } catch (Throwable) {
            throw new ZoneNotFound();
        }
    }
}