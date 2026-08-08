<?php

namespace App\Sale\Purchase\Infrastructure\Persistence;

use App\Sale\Purchase\Domain\Exceptions\PurchaseNotCreated;
use App\Sale\Purchase\Domain\Exceptions\PurchaseNotDeleted;
use App\Sale\Purchase\Domain\Exceptions\PurchaseNotUpdated;
use App\Sale\Purchase\Domain\Purchase;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Purchase\Domain\PurchaseRepository;
use App\Sale\Purchase\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class PurchaseDoctrineRepository implements PurchaseRepository
{
    private const string PURCHASE_PREFIX = 'p';

    public function __construct(private EntityManagerInterface $entityManager, private PurchaseMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Purchase $purchase): void
    {
        try {
            $entity = $this->mapper->newEntity($purchase);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PurchaseNotCreated();
        }
    }

    #[Override]
    public function update(Purchase $purchase): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $purchase->id()->value());
            $this->mapper->update($entity, $purchase);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PurchaseNotUpdated();
        }
    }

    #[Override]
    public function delete(Purchase $purchase): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $purchase->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PurchaseNotDeleted();
        }
    }

    #[Override]
    public function findById(PurchaseId $id, UserId $companyId): ?Purchase
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'company' => $companyId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::PURCHASE_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['attendee'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::PURCHASE_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['attendee'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::PURCHASE_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}