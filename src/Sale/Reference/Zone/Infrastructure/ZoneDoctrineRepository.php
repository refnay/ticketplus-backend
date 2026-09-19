<?php

namespace App\Sale\Reference\Zone\Infrastructure;

use App\Sale\Reference\Zone\Domain\Zone;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Sale\Reference\Zone\Domain\ZoneRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class ZoneDoctrineRepository implements ZoneRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Override]
    public function findById(ZoneId $id): ?Zone
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'zone', 'z')
            ->select(
                'z.name',
                'z.price',
                '(z.total_quantity - z.sold_quantity - z.reserved_quantity) AS quantity',
                'z.numbered_seating',
                'z.day_id',
            )
            ->equals('id', $id->value())
            ->fetchAssociative();

        if (is_null($result)) {
            return null;
        }

        return Zone::create(
            $id->value(),
            (string) $result['name'],
            (float) $result['price'],
            (int) $result['quantity'],
            (bool) $result['numbered_seating'],
            (string) $result['day_id'],
        );
    }
}
