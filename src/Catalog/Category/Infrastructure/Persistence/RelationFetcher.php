<?php

namespace App\Catalog\Category\Infrastructure\Persistence;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Shared\Domain\Exceptions\CompanyNotFound;
use App\Shared\Infrastructure\Persistence\Entity\Company as CompanyEntity;
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
}