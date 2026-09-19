<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventSlug;
use App\Catalog\Event\Domain\EventStatusList;
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
    private const string EVENT_PREFIX = 'e';

    public function __construct(private EntityManagerInterface $entityManager, private EventMapper $mapper) {}

    #[Override]
    public function save(Event $event): void
    {
        try {
            $entity = $this->mapper->newEntity($event);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable $e) {
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
            ->findOneBy(['id' => $id->value(), 'company' => $companyId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findByDayId(EventDayId $dayId, CompanyId $companyId): ?Event
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::EVENT_PREFIX)
        );

        $queryBuilder->innerJoin('days', 'd')
            ->equals('id', $dayId->value(), 'd')
            ->equals('company', $companyId->value());

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findPublishedById(EventId $id): ?Event
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'status' => EventStatusList::PUBLISHED->value]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findPublishedByDayId(EventDayId $dayId): ?Event
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::EVENT_PREFIX)
        );

        $queryBuilder->innerJoin('days', 'd')
            ->equals('id', $dayId->value(), 'd')
            ->equals('status', EventStatusList::PUBLISHED->value);

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findBySlug(EventSlug $slug, CompanyId $companyId): ?Event
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['slug' => $slug->value(), 'company' => $companyId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::EVENT_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->likeMultiple(['name', 'venue'], $filters['value'] ?? null, true)
            ->equals('country', $filters['country'] ?? null)
            ->equals('city', $filters['city'] ?? null)
            ->equals('category', $filters['category'] ?? null)
            ->equals('status', $filters['status'] ?? null);

        if (isset($filters['date'])) {
            $queryBuilder->innerJoin('days', 'd')
                ->greaterThan('date', $filters['date'], 'd')
                ->groupBy(self::EVENT_PREFIX . '.id');
        }

        $queryBuilder->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();

        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::EVENT_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->likeMultiple(['name', 'venue'], $filters['value'] ?? null, true)
            ->equals('country', $filters['country'] ?? null)
            ->equals('city', $filters['city'] ?? null)
            ->equals('category', $filters['category'] ?? null)
            ->equals('status', $filters['status'] ?? null);

        if (isset($filters['date'])) {
            $queryBuilder->innerJoin('days', 'd')
                ->greaterThan('date', $filters['date'], 'd');
        }

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(DISTINCT ' . self::EVENT_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
