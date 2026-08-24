<?php

namespace App\Sale\EventDay\Infrastructure;

use App\Sale\EventDay\Domain\EventDay;
use App\Sale\EventDay\Domain\EventDayId;
use App\Sale\EventDay\Domain\EventDayRepository;
use App\Sale\Event\Domain\EventId;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class EventDayDoctrineRepository implements EventDayRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(EventId $eventId, EventDayId $id): ?EventDay
    {
        $sql = sprintf(
            "SELECT d.date,
                e.currency,
                e.tax_rate,
                e.name AS event_name
            FROM day d
            INNER JOIN event e ON e.id = d.event_id
            WHERE d.id = '%s'
                AND e.id = '%s'",
            $id->value(),
            $eventId->value(),
        );

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql)
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return EventDay::create(
            $id->value(),
            (string) $result['currency'],
            (string) $result['event_name'],
            (string) $result['date'],
            (float) $result['tax_rate'],
        );
    }
}
