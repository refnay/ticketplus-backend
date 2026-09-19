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
use App\Shared\Domain\Enums\ReportIntervalList;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class OrderDoctrineRepository implements OrderRepository
{
    private const string ORDER_PREFIX = 'o';

    public function __construct(private EntityManagerInterface $entityManager, private OrderMapper $mapper) {}

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
        $result = $this->approvedOrdersQuery($companyId, $from, $to)
            ->select(sprintf('COALESCE(SUM(%s), 0) AS amount', $this->salesAmountExpression()))
            ->setParameter('currency', $currency->value())
            ->fetchAssociative();

        return (float) ($result['amount'] ?? 0.00);
    }

    #[Override]
    public function approvedSalesEvolution(
        CompanyId $companyId,
        OrderCurrency $currency,
        OrderPaidAt $from,
        OrderPaidAt $to,
        string $interval,
    ): array {
        $dateExpression = match (ReportIntervalList::from($interval)) {
            ReportIntervalList::DAY => 'DATE(o.paid_at)',
            ReportIntervalList::WEEK => "DATE(DATE_TRUNC('week', o.paid_at))",
            ReportIntervalList::MONTH => "DATE(DATE_TRUNC('month', o.paid_at))",
        };

        $result = $this->approvedOrdersQuery($companyId, $from, $to)
            ->select(
                "{$dateExpression} AS date",
                sprintf('COALESCE(SUM(%s), 0) AS amount', $this->salesAmountExpression()),
            )
            ->setParameter('currency', $currency->value())
            ->groupBy($dateExpression)
            ->orderBy('date', 'ASC')
            ->fetchAllAssociative();

        return array_map(static fn(array $sale): array => [
            'date' => (string) $sale['date'],
            'amount' => round((float) $sale['amount'], 2),
        ], $result);
    }

    #[Override]
    public function approvedSalesByEvent(
        CompanyId $companyId,
        OrderCurrency $currency,
        OrderPaidAt $from,
        OrderPaidAt $to,
        int $limit,
    ): array {
        $amountExpression = sprintf('COALESCE(SUM(%s), 0)', $this->salesAmountExpression());

        $result = $this->approvedOrdersQuery($companyId, $from, $to)
            ->select('e.id', 'e.name', "{$amountExpression} AS amount")
            ->setParameter('currency', $currency->value())
            ->groupBy('e.id', 'e.name')
            ->having("{$amountExpression} > 0")
            ->orderBy('amount', 'DESC')
            ->addOrderBy('e.name', 'ASC')
            ->maxResults($limit)
            ->fetchAllAssociative();

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
        $result = $this->approvedOrdersQuery($companyId, $from, $to)
            ->select('COUNT(o.id) AS quantity')
            ->fetchAssociative();

        return (int) ($result['quantity'] ?? 0);
    }

    private function approvedOrdersQuery(
        CompanyId $companyId,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): NativeQueryBuilder {
        return NativeQueryBuilder::from($this->entityManager->getConnection(), 'purchase', self::ORDER_PREFIX)
            ->innerJoin('event', 'e', 'e.id = o.event_id')
            ->equals('company_id', $companyId->value(), 'e')
            ->equals('status', OrderStatusList::PAID->value)
            ->greaterOrEqual('paid_at', $from->__toString(), null, 'from')
            ->lessThan('paid_at', $to->__toString(), null, 'to');
    }

    private function salesAmountExpression(): string
    {
        return "CASE
            WHEN o.currency = :currency THEN o.total
            WHEN :currency = 'PEN' AND o.currency = 'USD' AND o.exchange_rate > 0
                THEN o.total * o.exchange_rate
            WHEN :currency = 'USD' AND o.currency = 'PEN' AND o.exchange_rate > 0
                THEN o.total / o.exchange_rate
            ELSE 0
        END";
    }
}
