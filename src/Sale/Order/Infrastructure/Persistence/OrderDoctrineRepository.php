<?php

namespace App\Sale\Order\Infrastructure\Persistence;

use App\Sale\Order\Domain\Exceptions\OrderNotCreated;
use App\Sale\Order\Domain\Exceptions\OrderNotDeleted;
use App\Sale\Order\Domain\Exceptions\OrderNotUpdated;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderExpiresAt;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatusList;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class OrderDoctrineRepository implements OrderRepository
{
    private const string ORDER_PREFIX = 'o';

    public function __construct(private EntityManagerInterface $entityManager, private OrderMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Order $order): void
    {
        try {
            $entity = $this->mapper->newEntity($order);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new OrderNotCreated();
        }
    }

    #[Override]
    public function update(Order $order): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $order->id()->value());
            $this->mapper->update($entity, $order);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new OrderNotUpdated();
        }
    }

    #[Override]
    public function delete(Order $order): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $order->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new OrderNotDeleted();
        }
    }

    #[Override]
    public function find(OrderId $id): ?Order
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findById(OrderId $id, UserId $userId): ?Order
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'attendee' => $userId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findExpireds(): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ORDER_PREFIX)
        );
        
        $queryBuilder->equals('status', OrderStatusList::PENDING->value)
            ->lessOrEqual('expiresAt', OrderExpiresAt::now()->value());

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();

        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ORDER_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['user'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::ORDER_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['user'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::ORDER_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}