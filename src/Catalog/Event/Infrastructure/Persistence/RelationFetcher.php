<?php

namespace App\Catalog\Event\Infrastructure\Persistence;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Shared\Domain\Exceptions\CompanyNotFound;
use App\Shared\Infrastructure\Persistence\Entity\Company as CompanyEntity;
use App\Shared\Infrastructure\Persistence\Entity\Category as CategoryEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    
    public function company(CompanyId $id): CompanyEntity
    {
        try {
            return $this->entityManager->getReference(CompanyEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new CompanyNotFound();
        }
    }

    public function category(CategoryId $id): CategoryEntity
    {
        try {
            return $this->entityManager->getReference(CategoryEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new CompanyNotFound();
        }
    }
}