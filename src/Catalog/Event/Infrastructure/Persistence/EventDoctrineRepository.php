<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventNotCreated;
use App\Catalog\Event\Domain\Exceptions\EventNotDeleted;
use App\Catalog\Event\Domain\Exceptions\EventNotUpdated;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class EventDoctrineRepository implements EventRepository
{
    private const string CATEGORY_PREFIX = 'e';

    public function __construct(private EntityManagerInterface $entityManager, private EventMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Event $event): void
    {
        try {
            $entity = $this->mapper->newEntity($event);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new EventNotCreated();
        }
    }

    #[Override]
    public function update(Event $event): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $event->id()->value());
            $this->mapper->update($entity, $event);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new EventNotUpdated();
        }
    }

    #[Override]
    public function delete(Event $event): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $event->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new EventNotDeleted();
        }
    }

    #[Override]
    public function findById(EventId $id, CompanyId $companyId): ?Event
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::CATEGORY_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true)
            ->equals('country', $filters['country'] ?? null)
            ->equals('city', $filters['city'] ?? null)
            ->equals('status', $filters['status'] ?? null)
            ->applyOrder($orderBy, $order);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::CATEGORY_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true)
            ->equals('country', $filters['country'] ?? null)
            ->equals('city', $filters['city'] ?? null)
            ->equals('status', $filters['status'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::CATEGORY_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}