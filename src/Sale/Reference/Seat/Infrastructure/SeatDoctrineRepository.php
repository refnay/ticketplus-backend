<?php

namespace App\Sale\Reference\Seat\Infrastructure;

use App\Sale\Reference\Seat\Domain\Seat;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Seat\Domain\SeatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class SeatDoctrineRepository implements SeatRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(SeatId $id): ?Seat
    {
        $sql = "SELECT
                s.code,
                s.status,
                s.zone_id
            FROM seat s
            WHERE s.id = :id";

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql, ['id' => $id->value()])
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return Seat::create(
            $id->value(),
            (string) $result['code'],
            (int) $result['status'],
            (string) $result['zone_id'],
        );
    }
}
