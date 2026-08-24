<?php

namespace App\Sale\Reference\EventDay\Infrastructure;

use App\Sale\Reference\EventDay\Domain\EventDay;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\EventDay\Domain\EventDayRepository;
use App\Sale\Reference\Event\Domain\EventId;
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
        $sql = 'SELECT d.id, d.date, d.event_id
                FROM day d
                WHERE d.id = :id
                    AND d.event_id = :event';

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql, [
                'id' => $id->value(),
                'event' => $eventId->value(),
            ])
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return EventDay::create(
            (string) $result['id'],
            (string) $result['date'],
            (string) $result['event_id'],
        );
    }
}
