<?php

namespace App\Account\Company\Infrastructure\Persistence;

use App\Account\Company\Domain\CompanyMemberRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class CompanyMemberDoctrineRepository implements CompanyMemberRepository
{
    private const string COMPANY_MEMBER_PREFIX = 'cm';

    public function __construct(private EntityManagerInterface $entityManager, private CompanyMemberMapper $mapper)
    {
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::COMPANY_MEMBER_PREFIX)
        );

        $queryBuilder->equals('user', $filters['user'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::COMPANY_MEMBER_PREFIX)
        );

        $queryBuilder->equals('user', $filters['user'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::COMPANY_MEMBER_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}