<?php

namespace App\Sale\Reference\Event\Infrastructure;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Reference\Event\Domain\Event;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Event\Domain\EventRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class EventDoctrineRepository implements EventRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Override]
    public function findById(EventId $id, ?CompanyId $companyId = null): ?Event
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), 'event', 'e')
            ->select('e.id', 'e.currency', 'e.name', 'e.tax_rate')
            ->equals('id', $id->value())
            ->equals('company_id', $companyId?->value())
            ->fetchAssociative();

        if (is_null($result)) {
            return null;
        }

        return Event::create(
            (string) $result['id'],
            (string) $result['currency'],
            (string) $result['name'],
            (float) $result['tax_rate'],
        );
    }
}
