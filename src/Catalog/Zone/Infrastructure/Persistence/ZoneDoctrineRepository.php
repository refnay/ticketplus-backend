<?php

namespace App\Catalog\Zone\Infrastructure\Persistence;

use App\Catalog\Event\Domain\EventDayId;
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
    private const string CATEGORY_PREFIX = 'z';

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
    public function findById(ZoneId $id, EventDayId $dayId): ?Zone
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'day' => $dayId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::CATEGORY_PREFIX)
        );

        $queryBuilder->equals('day', $filters['day'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true)
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

        $queryBuilder->equals('day', $filters['day'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::CATEGORY_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}