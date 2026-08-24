<?php

namespace App\Sale\Reference\Zone\Infrastructure;

use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Zone\Domain\Zone;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Sale\Reference\Zone\Domain\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class ZoneDoctrineRepository implements ZoneRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(ZoneId $id, EventId $eventId, EventDayId $dayId): ?Zone
    {
        $sql = sprintf(
            "SELECT
                z.name,
                z.price,
                (z.total_quantity - z.sold_quantity - z.reserved_quantity) AS quantity,
                z.numbered_seating
            FROM zone z
            INNER JOIN day d ON d.id = z.day_id
            INNER JOIN event e ON e.id = d.event_id
            WHERE z.id = '%s'
                AND d.id = '%s'
                AND e.id = '%s'",
            $id->value(),
            $dayId->value(),
            $eventId->value(),
        );

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql)
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return Zone::create(
            $id->value(),
            (string) $result['name'],
            (float) $result['price'],
            (int) $result['quantity'],
            (bool) $result['numbered_seating'],
        );
    }
}
