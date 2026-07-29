<?php

namespace App\Catalog\Category\Infrastructure\Persistence;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Category\Domain\Exceptions\CategoryNotCreated;
use App\Catalog\Category\Domain\Exceptions\CategoryNotDeleted;
use App\Catalog\Category\Domain\Exceptions\CategoryNotUpdated;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Doctrine\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class CategoryDoctrineRepository implements CategoryRepository
{
    private const string CATEGORY_PREFIX = 'c';

    public function __construct(private EntityManagerInterface $entityManager, private CategoryMapper $mapper)
    {
    }
    
    #[Override]
    public function save(Category $category): void
    {
        try {
            $entity = $this->mapper->newEntity($category);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CategoryNotCreated();
        }
    }

    #[Override]
    public function update(Category $category): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $category->id()->value());
            $this->mapper->update($entity, $category);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CategoryNotUpdated();
        }
    }

    #[Override]
    public function delete(Category $category): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $category->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CategoryNotDeleted();
        }
    }

    #[Override]
    public function findById(CategoryId $id, CompanyId $companyId): ?Category
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['id' => $id->value(), 'company' => $companyId->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
    
    #[Override]
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::CATEGORY_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->equals('reference', $filters['reference'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true)
            ->applyOrder($orderBy, $order)
            ->paginate($limit, $offset);

        $entities = $queryBuilder->queryBuilder()->getQuery()->getResult();
        
        return array_map(fn($entity) => $this->mapper->newDomain($entity), $entities);
    }

    #[Override]
    public function countByFilters(array $filters): int
    {
        $queryBuilder = QueryBuilder::from(
            $this->entityManager->getRepository($this->mapper->entityClass())->createQueryBuilder(self::CATEGORY_PREFIX)
        );

        $queryBuilder->equals('company', $filters['company'] ?? null)
            ->equals('reference', $filters['reference'] ?? null)
            ->likeMultiple(['name'], $filters['name'] ?? null, true);

        return (int) $queryBuilder->queryBuilder()
            ->select('COUNT(' . self::CATEGORY_PREFIX . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    } 
}