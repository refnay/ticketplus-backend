<?php

namespace App\Feedback\Review\Infrastructure\Persistence;

use App\Feedback\Review\Domain\Exceptions\ReviewNotCreated;
use App\Feedback\Review\Domain\Exceptions\ReviewNotDeleted;
use App\Feedback\Review\Domain\Exceptions\ReviewNotUpdated;
use App\Feedback\Review\Domain\Review;
use App\Feedback\Review\Domain\ReviewId;
use App\Feedback\Review\Domain\ReviewRepository;
use App\Feedback\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class ReviewDoctrineRepository implements ReviewRepository
{
    private const string REVIEW_PREFIX = 'r';

    public function __construct(private EntityManagerInterface $entityManager, private ReviewMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Review $review): void
    {
        try {
            $entity = $this->mapper->newEntity($review);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ReviewNotCreated();
        }
    }

    #[Override]
    public function update(Review $review): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $review->id()->value());
            $this->mapper->update($entity, $review);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ReviewNotUpdated();
        }
    }

    #[Override]
    public function delete(Review $review): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $review->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new ReviewNotDeleted();
        }
    }

    #[Override]
    public function findById(ReviewId $id, UserId $userId): ?Review
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'attendee' => $userId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::REVIEW_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['user'] ?? null)
            ->equals('event', $filters['event'] ?? null)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::REVIEW_PREFIX)
        );

        $queryBuilder->equals('attendee', $filters['user'] ?? null)
            ->equals('event', $filters['event'] ?? null);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::REVIEW_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}