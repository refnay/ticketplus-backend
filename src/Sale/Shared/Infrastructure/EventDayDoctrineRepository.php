<?php

namespace App\Sale\Shared\Infrastructure;

use App\Sale\Shared\Domain\EventDay;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventDayRepository;
use App\Sale\Shared\Domain\EventId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Override;

class EventDayDoctrineRepository implements EventDayRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(EventId $eventId, EventDayId $id): ?EventDay
    {
        $row = $this->entityManager->createNativeQuery(
            'SELECT
                d.date,
                e.currency,
                e.tax_rate,
                e.name AS event_name
            FROM day d
            INNER JOIN event e ON e.id = d.event_id
            WHERE d.id = :id
                AND e.id = :event',
            $this->resultSetMapping()
        )
            ->setParameter('id', $id->value())
            ->setParameter('event', $eventId->value())
            ->getOneOrNullResult();

        if ($row === null) {
            return null;
        }

        return EventDay::create(
            $id->value(),
            (string) $row['date'],
            (string) $row['currency'],
            (float) $row['tax_rate'],
            (string) $row['eventName'],
        );
    }

    private function resultSetMapping(): ResultSetMapping
    {
        $mapping = new ResultSetMapping();
        $mapping->addScalarResult('date', 'date');
        $mapping->addScalarResult('currency', 'currency');
        $mapping->addScalarResult('tax_rate', 'tax_rate');
        $mapping->addScalarResult('event_name', 'eventName');

        return $mapping;
    }
}
