<?php

namespace App\Sale\Payment\Infrastructure\Persistence;

use App\Sale\Order\Domain\Exceptions\OrderNotFound;
use App\Sale\Order\Domain\OrderId;
use App\Shared\Infrastructure\Persistence\Entity\Purchase as OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function order(OrderId $id): OrderEntity
    {
        try {
            return $this->entityManager->getReference(OrderEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new OrderNotFound();
        }
    }
}