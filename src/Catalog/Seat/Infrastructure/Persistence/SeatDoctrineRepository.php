<?php

namespace App\Catalog\Seat\Infrastructure\Persistence;

use App\Catalog\Seat\Domain\Exceptions\SeatNotCreated;
use App\Catalog\Seat\Domain\Exceptions\SeatNotDeleted;
use App\Catalog\Seat\Domain\Exceptions\SeatNotUpdated;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class SeatDoctrineRepository implements SeatRepository
{
    private const string SEAT_PREFIX = 's';

    public function __construct(private EntityManagerInterface $entityManager, private SeatMapper $mapper)
    {
    }

    #[Override]
    public function save(Seat $seat): void
    {
        try {
            $entity = $this->mapper->newEntity($seat);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new SeatNotCreated();
        }
    }

    #[Override]
    public function update(Seat $seat): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $seat->id()->value());
            $this->mapper->update($entity, $seat);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new SeatNotUpdated();
        }
    }

    #[Override]
    public function delete(Seat $seat): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $seat->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new SeatNotDeleted();
        }
    }

    #[Override]
    public function find(SeatId $id): ?Seat
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findById(SeatId $id, CompanyId $companyId): ?Seat
    {
        $query = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->createQueryBuilder(self::SEAT_PREFIX);
        $entity = $query
            ->innerJoin(self::SEAT_PREFIX . '.zone', 'z')
            ->innerJoin('z.day', 'd')
            ->innerJoin('d.event', 'e')
            ->andWhere(self::SEAT_PREFIX . '.id = :id')
            ->andWhere('e.company = :companyId')
            ->setParameter('id', $id->value())
            ->setParameter('companyId', $companyId->value())
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findByCode(SeatCode $code, ZoneId $zoneId): ?Seat
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['code' => $code->value(), 'zone' => $zoneId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::SEAT_PREFIX)
        );

        $queryBuilder->equals('zone', $filters['zone'] ?? null)
            ->equals('numberedSeating', $filters['numberedSeating'] ?? null)
            ->equals('status', $filters['status'] ?? null)
            ->likeMultiple(['code'], $filters['code'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->queryBuilder()
                ->innerJoin(self::SEAT_PREFIX . '.zone', 'company_zone')
                ->innerJoin('company_zone.day', 'company_day')
                ->innerJoin('company_day.event', 'company_event')
                ->andWhere('company_event.company = :company')
                ->setParameter('company', $filters['company']);
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
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::SEAT_PREFIX)
        );

        $queryBuilder->equals('zone', $filters['zone'] ?? null)
            ->equals('numberedSeating', $filters['numberedSeating'] ?? null)
            ->equals('status', $filters['status'] ?? null)
            ->likeMultiple(['code'], $filters['code'] ?? null, true);

        if (isset($filters['company'])) {
            $queryBuilder->queryBuilder()
                ->innerJoin(self::SEAT_PREFIX . '.zone', 'company_zone')
                ->innerJoin('company_zone.day', 'company_day')
                ->innerJoin('company_day.event', 'company_event')
                ->andWhere('company_event.company = :company')
                ->setParameter('company', $filters['company']);
        }

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::SEAT_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
