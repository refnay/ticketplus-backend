<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Event\Domain\EventStatusList;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotCreated;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotDeleted;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotUpdated;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class ZoneDoctrineRepository implements ZoneRepository
{
    private const string ZONE_PREFIX = 'z';

    public function __construct(private EntityManagerInterface $entityManager, private ZoneMapper $mapper) {}

    #[Override]
    public function save(Zone $zone): void
    {
        try {
            $entity = $this->mapper->newEntity($zone);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ZoneNotCreated();
        }
    }

    #[Override]
    public function update(Zone $zone): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $zone->id()->value());
            $this->mapper->update($entity, $zone);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ZoneNotUpdated();
        }
    }

    #[Override]
    public function delete(Zone $zone): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $zone->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ZoneNotDeleted();
        }
    }

    #[Override]
    public function find(ZoneId $id): ?Zone
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findById(ZoneId $id, CompanyId $companyId): ?Zone
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ZONE_PREFIX)
        );

        $queryBuilder->innerJoin('day', 'd')
            ->innerJoin('event', 'e', 'd')
            ->equals('id', $id->value())
            ->equals('company', $companyId->value(), 'e');

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findPublishedById(ZoneId $id): ?Zone
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ZONE_PREFIX)
        );

        $queryBuilder->innerJoin('day', 'd')
            ->innerJoin('event', 'e', 'd')
            ->equals('id', $id->value())
            ->equals('status', EventStatusList::PUBLISHED->value, 'e');

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ZONE_PREFIX)
        );

        $queryBuilder->equals('day', $filters['day'] ?? null)
            ->equals('numberedSeating', $filters['numberedSeating'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->innerJoin('day', 'company_day')
                ->innerJoin('event', 'company_event', 'company_day')
                ->equals('company', $filters['company'], 'company_event');
        }

        if (($filters['availableOnly'] ?? false) === true) {
            $queryBuilder->andWhere(
                sprintf(
                    '%s.totalQuantity - %s.soldQuantity - %s.reservedQuantity > 0',
                    self::ZONE_PREFIX,
                    self::ZONE_PREFIX,
                    self::ZONE_PREFIX,
                ),
            );
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
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ZONE_PREFIX)
        );

        $queryBuilder->equals('day', $filters['day'] ?? null)
            ->equals('numberedSeating', $filters['numberedSeating'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->innerJoin('day', 'company_day')
                ->innerJoin('event', 'company_event', 'company_day')
                ->equals('company', $filters['company'], 'company_event');
        }

        if (($filters['availableOnly'] ?? false) === true) {
            $queryBuilder->andWhere(
                sprintf(
                    '%s.totalQuantity - %s.soldQuantity - %s.reservedQuantity > 0',
                    self::ZONE_PREFIX,
                    self::ZONE_PREFIX,
                    self::ZONE_PREFIX,
                ),
            );
        }

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::ZONE_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function occupancySummary(CompanyId $companyId): array
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'zone', 'z')
            ->select(
                'COALESCE(SUM(z.total_quantity), 0) AS total',
                'COALESCE(SUM(z.sold_quantity), 0) AS sold',
                'COALESCE(SUM(z.reserved_quantity), 0) AS reserved',
            )
            ->innerJoin('day', 'd', 'd.id = z.day_id')
            ->innerJoin('event', 'e', 'e.id = d.event_id', 'd')
            ->equals('company_id', $companyId->value(), 'e')
            ->fetchAssociative();

        return [
            'total' => (int) ($result['total'] ?? 0),
            'sold' => (int) ($result['sold'] ?? 0),
            'reserved' => (int) ($result['reserved'] ?? 0),
        ];
    }
}
