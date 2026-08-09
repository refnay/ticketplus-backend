<?php

namespace App\Sale\Purchase\Infrastructure\Persistence;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\Exceptions\DiscountNotFound;
use App\Sale\Shared\Domain\Exceptions\UserNotFound;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
use App\Shared\Infrastructure\Persistence\Entity\Discount as DiscountEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    
    public function user(UserId $id): UserEntity
    {
        try {
            return $this->entityManager->getReference(UserEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new UserNotFound();
        }
    }

    public function discount(DiscountId $id): DiscountEntity
    {
        try {
            return $this->entityManager->getReference(DiscountEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new DiscountNotFound();
        }
    }
}