<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventStatusList;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotCreated;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotDeleted;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotUpdated;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class ZoneDoctrineRepository implements ZoneRepository
{
    private const string ZONE_PREFIX = 'z';

    public function __construct(private EntityManagerInterface $entityManager, private ZoneMapper $mapper)
    {
    }

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
        $query = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->createQueryBuilder(self::ZONE_PREFIX);
        $entity = $query
            ->innerJoin(self::ZONE_PREFIX . '.day', 'd')
            ->innerJoin('d.event', 'e')
            ->andWhere(self::ZONE_PREFIX . '.id = :id')
            ->andWhere('e.company = :companyId')
            ->setParameter('id', $id->value())
            ->setParameter('companyId', $companyId->value())
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findPublishedById(ZoneId $id): ?Zone
    {
        $query = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->createQueryBuilder(self::ZONE_PREFIX);
        $entity = $query
            ->innerJoin(self::ZONE_PREFIX . '.day', 'd')
            ->innerJoin('d.event', 'e')
            ->andWhere(self::ZONE_PREFIX . '.id = :id')
            ->andWhere('e.status = :status')
            ->setParameter('id', $id->value())
            ->setParameter('status', EventStatusList::PUBLISHED->value)
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
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->queryBuilder()
                ->innerJoin(self::ZONE_PREFIX . '.day', 'company_day')
                ->innerJoin('company_day.event', 'company_event')
                ->andWhere('company_event.company = :company')
                ->setParameter('company', $filters['company']);
        }

        if (($filters['availableOnly'] ?? false) === true) {
            $queryBuilder->queryBuilder()->andWhere(
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
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->queryBuilder()
                ->innerJoin(self::ZONE_PREFIX . '.day', 'company_day')
                ->innerJoin('company_day.event', 'company_event')
                ->andWhere('company_event.company = :company')
                ->setParameter('company', $filters['company']);
        }

        if (($filters['availableOnly'] ?? false) === true) {
            $queryBuilder->queryBuilder()->andWhere(
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
        $sql = "SELECT
                COALESCE(SUM(z.total_quantity), 0) AS total,
                COALESCE(SUM(z.sold_quantity), 0) AS sold,
                COALESCE(SUM(z.reserved_quantity), 0) AS reserved
            FROM zone z
            INNER JOIN day d ON d.id = z.day_id
            INNER JOIN event e ON e.id = d.event_id
            WHERE e.company_id = :companyId";

        $result = $this->entityManager->getConnection()->executeQuery($sql, [
            'companyId' => $companyId->value(),
        ])->fetchAssociative();

        return [
            'total' => is_array($result) && isset($result['total']) ? (int) $result['total'] : 0,
            'sold' => is_array($result) && isset($result['sold']) ? (int) $result['sold'] : 0,
            'reserved' => is_array($result) && isset($result['reserved']) ? (int) $result['reserved'] : 0,
        ];
    }
}
