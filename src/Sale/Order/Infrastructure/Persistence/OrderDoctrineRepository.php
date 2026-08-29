<?php

namespace App\Sale\Order\Infrastructure\Persistence;

use App\Sale\Order\Domain\Exceptions\OrderNotCreated;
use App\Sale\Order\Domain\Exceptions\OrderNotDeleted;
use App\Sale\Order\Domain\Exceptions\OrderNotUpdated;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderExpiresAt;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatusList;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class OrderDoctrineRepository implements OrderRepository
{
    private const string ORDER_PREFIX = 'o';
    private const int APPROVED_SALES_BY_EVENT_LIMIT = 4;

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

    #[Override]
    public function approvedSalesTotal(
        CompanyId $companyId,
        OrderCurrency $currency,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): float {
        $sql = sprintf(
            "SELECT
                COALESCE(SUM(
                    CASE
                        WHEN o.currency = :currency THEN o.total
                        WHEN :currency = 'PEN' AND o.currency = 'USD' AND o.exchange_rate > 0
                            THEN o.total * o.exchange_rate
                        WHEN :currency = 'USD' AND o.currency = 'PEN' AND o.exchange_rate > 0
                            THEN o.total / o.exchange_rate
                        ELSE 0
                    END
                ), 0) AS amount
            FROM purchase o
            INNER JOIN event e ON e.id = o.event_id
            WHERE e.company_id = :companyId
              AND o.status = %d
              AND o.paid_at >= :from
              AND o.paid_at < :to",
            OrderStatusList::PAID->value,
        );

        $result = $this->entityManager->getConnection()->executeQuery($sql, [
            'companyId' => $companyId->value(),
            'currency' => $currency->value(),
            'from' => $from->__toString(),
            'to' => $to->__toString(),
        ])->fetchAssociative();

        return is_array($result) && isset($result['amount']) ? (float) $result['amount'] : 0.00;
    }

    #[Override]
    public function approvedSalesByEvent(
        CompanyId $companyId,
        OrderCurrency $currency,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): array {
        $sql = sprintf(
            "SELECT
                e.id,
                e.name,
                COALESCE(SUM(
                    CASE
                        WHEN o.currency = :currency THEN o.total
                        WHEN :currency = 'PEN' AND o.currency = 'USD' AND o.exchange_rate > 0
                            THEN o.total * o.exchange_rate
                        WHEN :currency = 'USD' AND o.currency = 'PEN' AND o.exchange_rate > 0
                            THEN o.total / o.exchange_rate
                        ELSE 0
                    END
                ), 0) AS amount
            FROM purchase o
            INNER JOIN event e ON e.id = o.event_id
            WHERE e.company_id = :companyId
              AND o.status = %d
              AND o.paid_at >= :from
              AND o.paid_at < :to
            GROUP BY e.id, e.name
            HAVING COALESCE(SUM(
                CASE
                    WHEN o.currency = :currency THEN o.total
                    WHEN :currency = 'PEN' AND o.currency = 'USD' AND o.exchange_rate > 0
                        THEN o.total * o.exchange_rate
                    WHEN :currency = 'USD' AND o.currency = 'PEN' AND o.exchange_rate > 0
                        THEN o.total / o.exchange_rate
                    ELSE 0
                END
            ), 0) > 0
            ORDER BY amount DESC, e.name ASC
            LIMIT %d",
            OrderStatusList::PAID->value,
            self::APPROVED_SALES_BY_EVENT_LIMIT,
        );

        $result = $this->entityManager->getConnection()->executeQuery($sql, [
            'companyId' => $companyId->value(),
            'currency' => $currency->value(),
            'from' => $from->__toString(),
            'to' => $to->__toString(),
        ])->fetchAllAssociative();

        return array_map(static fn(array $event): array => [
            'id' => (string) $event['id'],
            'name' => (string) $event['name'],
            'amount' => round((float) $event['amount'], 2),
        ], $result);
    }

    #[Override]
    public function countPaidOrders(
        CompanyId $companyId,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): int {
        $sql = sprintf(
            "SELECT COUNT(o.id) AS quantity
            FROM purchase o
            INNER JOIN event e ON e.id = o.event_id
            WHERE e.company_id = :companyId
              AND o.status = %d
              AND o.paid_at >= :from
              AND o.paid_at < :to",
            OrderStatusList::PAID->value,
        );

        $result = $this->entityManager->getConnection()->executeQuery($sql, [
            'companyId' => $companyId->value(),
            'from' => $from->__toString(),
            'to' => $to->__toString(),
        ])->fetchAssociative();

        return is_array($result) && isset($result['quantity']) ? (int) $result['quantity'] : 0;
    }
}
