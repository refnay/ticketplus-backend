<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

use App\Sale\Event\Domain\Event;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\EventRepository;
use App\Sale\Purchase\Domain\CompanyId;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class EventDoctrineRepository implements EventRepository
{
    public function __construct(private EntityManagerInterface $entityManager, private EventMapper $mapper)
    {
    }

    #[Override]
    public function findById(EventId $id, CompanyId $companyId): ?Event
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'company' => $companyId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
}