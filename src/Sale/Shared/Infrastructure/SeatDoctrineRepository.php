<?php

namespace App\Sale\Shared\Infrastructure;

use App\Sale\Shared\Domain\Seat;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\SeatRepository;
use App\Sale\Shared\Domain\ZoneId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Override;

class SeatDoctrineRepository implements SeatRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(SeatId $id, ZoneId $zoneId): ?Seat
    {
        $row = $this->entityManager->createNativeQuery(
            'SELECT
                s.code,
                s.status
            FROM seat s
            INNER JOIN zone z ON z.id = s.zone_id
            WHERE s.id = :id
                AND z.id = :zone',
            $this->resultSetMapping()
        )
            ->setParameter('id', $id->value())
            ->setParameter('zone', $zoneId->value())
            ->getOneOrNullResult();

        if ($row === null) {
            return null;
        }

        return Seat::create(
            $id->value(),
            (string) $row['code'],
            (string) $row['status'],
        );
    }

    private function resultSetMapping(): ResultSetMapping
    {
        $mapping = new ResultSetMapping();
        $mapping->addScalarResult('code', 'code');
        $mapping->addScalarResult('status', 'status');

        return $mapping;
    }
}
