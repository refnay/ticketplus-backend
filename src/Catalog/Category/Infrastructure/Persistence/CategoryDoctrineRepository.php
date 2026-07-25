<?php

namespace App\Catalog\Category\Infrastructure\Persistence;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Category\Domain\Exceptions\CategoryNotCreated;
use App\Catalog\Category\Domain\Exceptions\CategoryNotDeleted;
use App\Catalog\Category\Domain\Exceptions\CategoryNotUpdated;
use App\Catalog\Shared\Domain\CompanyId;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class CategoryDoctrineRepository implements CategoryRepository
{
    public function __construct(private EntityManagerInterface $entityManager, private CategoryMapper $mapper)
    {
    }
    
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

    public function findById(CategoryId $id, CompanyId $companyId): ?Category
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
}