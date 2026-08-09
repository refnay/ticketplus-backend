<?php

namespace App\Account\Member\Infrastructure\Persistence;

use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberId;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Member\Domain\Exceptions\MemberNotCreated;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class MemberDoctrineRepository implements MemberRepository
{
    private const string MEMBER_PREFIX = 'm';

    public function __construct(private EntityManagerInterface $entityManager, private MemberMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Member $member): void
    {
        try {
            $entity = $this->mapper->newEntity($member);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new MemberNotCreated();
        }
    }

    #[Override]
    public function findById(MemberId $id): ?Member
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
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::MEMBER_PREFIX)
        );

        $queryBuilder->equals('member', $filters['user'] ?? null)
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
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::MEMBER_PREFIX)
        );

        $queryBuilder->equals('member', $filters['user'] ?? null)
            ->equals('company', $filters['company'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::MEMBER_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}