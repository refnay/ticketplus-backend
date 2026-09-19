<?php

namespace App\Sale\Reference\EventDay\Infrastructure;

use App\Sale\Reference\EventDay\Domain\EventDay;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\EventDay\Domain\EventDayRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class EventDayDoctrineRepository implements EventDayRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Override]
    public function findById(EventDayId $id): ?EventDay
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'day', 'd')
            ->select('d.id', 'd.date', 'd.event_id')
            ->equals('id', $id->value())
            ->fetchAssociative();

        if (is_null($result)) {
            return null;
        }

        return EventDay::create(
            (string) $result['id'],
            (string) $result['date'],
            (string) $result['event_id'],
        );
    }
}
