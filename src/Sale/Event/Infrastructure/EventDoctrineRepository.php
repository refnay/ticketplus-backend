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
    public function findById(EventId $id, ?CompanyId $companyId = null): ?Event
    {
        $sql = 'SELECT e.id, e.currency, e.name, e.tax_rate
                FROM event e
                WHERE e.id = :id';

        $parameters = ['id' => $id->value()];

        if (!is_null($companyId)) {
            $sql .= ' AND e.company_id = :company';
            $parameters['company'] = $companyId->value();
        }

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql, $parameters)
            ->fetchAssociative();

        if (!is_array($result)) {
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
