<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderStatusList;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\Exceptions\TicketNotCreated;
use App\Sale\Ticket\Domain\Exceptions\TicketNotDeleted;
use App\Sale\Ticket\Domain\Exceptions\TicketNotUpdated;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketCode;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketQRCode;
use App\Sale\Ticket\Domain\TicketRepository;
use App\Sale\Reference\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class TicketDoctrineRepository implements TicketRepository
{
    private const string TICKET_PREFIX = 't';

    public function __construct(private EntityManagerInterface $entityManager, private TicketMapper $mapper) {}

    #[Override]
    public function save(Ticket $ticket): void
    {
        try {
            $entity = $this->mapper->newEntity($ticket);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new TicketNotCreated();
        }
    }

    #[Override]
    public function update(Ticket $ticket): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $ticket->id()->value());
            $this->mapper->update($entity, $ticket);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new TicketNotUpdated();
        }
    }

    #[Override]
    public function delete(Ticket $ticket): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $ticket->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new TicketNotDeleted();
        }
    }

    #[Override]
    public function findById(TicketId $id, UserId $userId): ?Ticket
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->innerJoin('purchase', 'o')
            ->equals('id', $id->value())
            ->equals('attendee', $userId->value(), 'o');

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findByQrCode(TicketQRCode $qrCode, CompanyId $companyId): ?Ticket
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->innerJoin('purchase', 'o')
            ->innerJoin('event', 'e', 'o')
            ->equals('QRCode', $qrCode->value())
            ->equals('company', $companyId->value(), 'e');

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findByCode(TicketCode $code, CompanyId $companyId): ?Ticket
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->innerJoin('purchase', 'o')
            ->innerJoin('event', 'e', 'o')
            ->equals('code', $code->value())
            ->equals('company', $companyId->value(), 'e');

        $entity = $queryBuilder->queryBuilder()
            ->getQuery()
            ->getOneOrNullResult();

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->innerJoin('purchase', 'o')
            ->innerJoin('event', 'e', 'o')
            ->equals('purchase', $filters['order'] ?? null)
            ->equals('id', $filters['event'] ?? null, 'e')
            ->equals('company', $filters['company'] ?? null, 'e')
            ->equals('status', $filters['status'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();

        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->innerJoin('purchase', 'o')
            ->innerJoin('event', 'e', 'o')
            ->equals('purchase', $filters['order'] ?? null)
            ->equals('id', $filters['event'] ?? null, 'e')
            ->equals('company', $filters['company'] ?? null, 'e')
            ->equals('status', $filters['status'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::TICKET_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function countSoldTickets(
        CompanyId $companyId,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): int {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'ticket', self::TICKET_PREFIX)
            ->select('COUNT(t.id) AS quantity')
            ->innerJoin('purchase', 'o', 'o.id = t.purchase_id')
            ->innerJoin('event', 'e', 'e.id = o.event_id', 'o')
            ->equals('company_id', $companyId->value(), 'e')
            ->equals('status', OrderStatusList::PAID->value, 'o')
            ->greaterOrEqual('paid_at', $from->__toString(), 'o', 'from')
            ->lessThan('paid_at', $to->__toString(), 'o', 'to')
            ->fetchAssociative();

        return (int) ($result['quantity'] ?? 0);
    }
}
