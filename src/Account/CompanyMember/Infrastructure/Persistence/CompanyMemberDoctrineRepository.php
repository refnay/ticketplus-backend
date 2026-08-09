<?php

namespace App\Account\CompanyMember\Infrastructure\Persistence;

use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberId;
use App\Account\CompanyMember\Domain\CompanyMemberRepository;
use App\Account\CompanyMember\Domain\Exceptions\CompanyMemberNotCreated;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class CompanyMemberDoctrineRepository implements CompanyMemberRepository
{
    private const string COMPANY_MEMBER_PREFIX = 'cm';

    public function __construct(private EntityManagerInterface $entityManager, private CompanyMemberMapper $mapper)
    {
    }
    
    #[Override]
    public function save(CompanyMember $companyMember): void
    {
        try {
            $entity = $this->mapper->newEntity($companyMember);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CompanyMemberNotCreated();
        }
    }

    #[Override]
    public function findById(CompanyMemberId $id): ?CompanyMember
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::COMPANY_MEMBER_PREFIX)
        );

        $queryBuilder->equals('user', $filters['user'] ?? null)
            ->equals('company', $filters['company'] ?? null)
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

        $queryBuilder->equals('user', $filters['user'] ?? null)
            ->equals('company', $filters['company'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::COMPANY_MEMBER_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}