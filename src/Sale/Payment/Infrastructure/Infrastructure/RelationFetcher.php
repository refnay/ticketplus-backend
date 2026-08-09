<?php

namespace App\Sale\Payment\Infrastructure\Persistence;

use App\Sale\Purchase\Domain\Exceptions\PurchaseNotFound;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Shared\Infrastructure\Persistence\Entity\Purchase as PurchaseEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function purchase(PurchaseId $id): PurchaseEntity
    {
        try {
            return $this->entityManager->getReference(PurchaseEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new PurchaseNotFound();
        }
    }
}