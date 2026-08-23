<?php

namespace App\Sale\Shared\Infrastructure;

use App\Sale\Shared\Domain\Seat;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\SeatRepository;
use App\Sale\Shared\Domain\ZoneId;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class SeatDoctrineRepository implements SeatRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(SeatId $id, ZoneId $zoneId): ?Seat
    {
        $sql = sprintf(
            "SELECT
                s.code,
                s.status
            FROM seat s
            INNER JOIN zone z ON z.id = s.zone_id
            WHERE s.id = '%s'
                AND z.id = '%s'",
            $id->value(),
            $zoneId->value(),
        );

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql)
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return Seat::create(
            $id->value(),
            (string) $result['code'],
            (int) $result['status'],
        );
    }
}