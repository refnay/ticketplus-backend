<?php

namespace App\Catalog\Category\Infrastructure\Persistence;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Infrastructure\Persistence\Entity\Category as CategoryEntity;

class CategoryMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Category $category): CategoryEntity
    {
        $entity = new CategoryEntity();
        
        $entity->setId($category->id()->toUuid());
        $entity->setName($category->name()->value());
        $entity->setReference($category->reference()->value());
        $entity->setCompany($this->fetcher->company($category->companyId()));

        return $entity;
    }

    public function newDomain(CategoryEntity $entity): Category
    {
        $category = new Category(
            CategoryId::fromString($entity->getId()),
            CategoryName::fromString($entity->getName()),
            CategoryReference::fromInt($entity->getReference()),
            CompanyId::fromString($entity->getCompany()->getId()),
        );

        return $category;
    }

    public function update(CategoryEntity $entity, Category $category): void
    {
        $entity->setName($category->name()->value());
        $entity->setReference($category->reference()->value());
    }

    public function entityClass(): string
    {
        return CategoryEntity::class;
    }
}