<?php

namespace App\Sale\Event\Infrastructure;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\Event;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class EventDoctrineRepository implements EventRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(EventId $id, CompanyId $companyId): ?Event
    {
        $sql = 'SELECT e.id
                FROM event e
                WHERE e.id = :id
                    AND e.company_id = :company';

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql, [
                'id' => $id->value(),
                'company' => $companyId->value(),
            ])
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return Event::create((string) $result['id']);
    }
}
