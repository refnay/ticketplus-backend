<?php

namespace App\Sale\Discount\Infrastructure\Persistence;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\Exceptions\DiscountNotCreated;
use App\Sale\Discount\Domain\Exceptions\DiscountNotDeleted;
use App\Sale\Discount\Domain\Exceptions\DiscountNotUpdated;
use App\Sale\Shared\Domain\EventId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class DiscountDoctrineRepository implements DiscountRepository
{
    private const string DISCOUNT_PREFIX = 'd';

    public function __construct(private EntityManagerInterface $entityManager, private DiscountMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Discount $discount): void
    {
        try {
            $entity = $this->mapper->newEntity($discount);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new DiscountNotCreated();
        }
    }

    #[Override]
    public function update(Discount $discount): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $discount->id()->value());
            $this->mapper->update($entity, $discount);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new DiscountNotUpdated();
        }
    }

    #[Override]
    public function delete(Discount $discount): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $discount->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new DiscountNotDeleted();
        }
    }

    #[Override]
    public function findById(DiscountId $id, EventId $eventId): ?Discount
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'event' => $eventId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::DISCOUNT_PREFIX)
        );

        $queryBuilder->equals('event', $filters['event'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::DISCOUNT_PREFIX)
        );

        $queryBuilder->equals('event', $filters['event'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::DISCOUNT_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}