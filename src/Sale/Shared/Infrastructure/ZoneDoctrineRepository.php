<?php

namespace App\Sale\Shared\Infrastructure;

use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Zone;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Shared\Domain\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Override;

class ZoneDoctrineRepository implements ZoneRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(ZoneId $id, EventId $eventId, EventDayId $dayId): ?Zone
    {
        $row = $this->entityManager->createNativeQuery(
            'SELECT
                z.name,
                z.currency,
                z.price,
                z.tax_rate,
                (z.total_quantity - z.sold_quantity - z.reserved_quantity) AS quantity,
                z.numbered_seating
            FROM zone z
            INNER JOIN day d ON d.id = z.day_id
            INNER JOIN event e ON e.id = d.event_id
            WHERE z.id = :id
                AND d.id = :day
                AND e.id = :event',
            $this->resultSetMapping()
        )
            ->setParameter('id', $id->value())
            ->setParameter('day', $dayId->value())
            ->setParameter('event', $eventId->value())
            ->getOneOrNullResult();

        if ($row === null) {
            return null;
        }

        return Zone::create(
            $id->value(),
            (string) $row['name'],
            (string) $row['currency'],
            (float) $row['price'],
            (float) $row['tax_rate'],
            (int) $row['quantity'],
            (bool) $row['numbered_seating'],
        );
    }

    private function resultSetMapping(): ResultSetMapping
    {
        $mapping = new ResultSetMapping();
        $mapping->addScalarResult('currency', 'currency');
        $mapping->addScalarResult('price', 'price');
        $mapping->addScalarResult('tax_rate', 'tax_rate');
        $mapping->addScalarResult('quantity', 'quantity');
        $mapping->addScalarResult('numbered_seating', 'numbered_seating');

        return $mapping;
    }
}
