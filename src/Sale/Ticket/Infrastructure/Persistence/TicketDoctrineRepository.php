<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderStatusList;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\Exceptions\TicketNotCreated;
use App\Sale\Ticket\Domain\Exceptions\TicketNotDeleted;
use App\Sale\Ticket\Domain\Exceptions\TicketNotUpdated;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketRepository;
use App\Sale\Reference\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class TicketDoctrineRepository implements TicketRepository
{
    private const string TICKET_PREFIX = 't';

    public function __construct(private EntityManagerInterface $entityManager, private TicketMapper $mapper)
    {
    }

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
        $query = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->createQueryBuilder(self::TICKET_PREFIX);
        $entity = $query
            ->innerJoin(self::TICKET_PREFIX . '.purchase', 'o')
            ->andWhere(self::TICKET_PREFIX . '.id = :id')
            ->andWhere('o.attendee = :userId')
            ->setParameter('id', $id->value())
            ->setParameter('userId', $userId->value())
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
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::TICKET_PREFIX)
        );

        $queryBuilder->equals('purchase', $filters['order'] ?? null);

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
        $sql = sprintf(
            "SELECT COUNT(t.id) AS quantity
            FROM ticket t
            INNER JOIN purchase o ON o.id = t.purchase_id
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
