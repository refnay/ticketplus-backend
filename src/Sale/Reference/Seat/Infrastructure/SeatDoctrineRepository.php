<?php

namespace App\Sale\Reference\Seat\Infrastructure;

use App\Sale\Reference\Seat\Domain\Seat;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Seat\Domain\SeatRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class SeatDoctrineRepository implements SeatRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Override]
    public function findById(SeatId $id): ?Seat
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'seat', 's')
            ->select('s.code', 's.status', 's.zone_id')
            ->equals('id', $id->value())
            ->fetchAssociative();

        if (is_null($result)) {
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
