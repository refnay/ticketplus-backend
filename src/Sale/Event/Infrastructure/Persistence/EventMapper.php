<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

use App\Sale\Event\Domain\Event;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\EventName;
use App\Sale\Event\Domain\EventStatus;
use App\Sale\Purchase\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Entity\Event as EventEntity;

class EventMapper
{
    public function newDomain(EventEntity $entity): Event
    {
        $event = new Event(
            EventId::fromString($entity->getId()),
            EventName::fromString($entity->getName()),
            EventStatus::fromInt($entity->getStatus()),
            CompanyId::fromString($entity->getCompany()->getId()),
        );

        return $event;
    }

    public function entityClass(): string
    {
        return EventEntity::class;
    }
}
