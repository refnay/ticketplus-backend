<?php

namespace App\Sale\Payment\Infrastructure\Persistence;

use App\Sale\Payment\Domain\Exceptions\PaymentNotCreated;
use App\Sale\Payment\Domain\Exceptions\PaymentNotDeleted;
use App\Sale\Payment\Domain\Exceptions\PaymentNotUpdated;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Order\Domain\OrderId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class PaymentDoctrineRepository implements PaymentRepository
{
    private const string PAYMENT_PREFIX = 'p';

    public function __construct(private EntityManagerInterface $entityManager, private PaymentMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Payment $payment): void
    {
        try {
            $entity = $this->mapper->newEntity($payment);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PaymentNotCreated();
        }
    }

    #[Override]
    public function update(Payment $payment): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $payment->id()->value());
            $this->mapper->update($entity, $payment);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PaymentNotUpdated();
        }
    }

    #[Override]
    public function delete(Payment $payment): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $payment->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new PaymentNotDeleted();
        }
    }

    #[Override]
    public function findById(PaymentId $id, OrderId $orderId): ?Payment
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'purchase' => $orderId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::PAYMENT_PREFIX)
        );

        $queryBuilder->equals('purchase', $filters['order'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::PAYMENT_PREFIX)
        );

        $queryBuilder->equals('purchase', $filters['order'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::PAYMENT_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}