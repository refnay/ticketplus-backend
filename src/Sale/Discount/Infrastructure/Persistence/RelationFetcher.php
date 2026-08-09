<?php

namespace App\Sale\Discount\Infrastructure\Persistence;

use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\Exceptions\EventNotFound;
use App\Shared\Infrastructure\Persistence\Entity\Event as EventEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    
    public function event(EventId $id): EventEntity
    {
        try {
            return $this->entityManager->getReference(EventEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new EventNotFound();
        }
    }
}