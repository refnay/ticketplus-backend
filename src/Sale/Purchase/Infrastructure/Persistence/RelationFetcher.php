<?php

namespace App\Sale\Purchase\Infrastructure\Persistence;

use App\Sale\Purchase\Domain\UserId;
use App\Sale\Shared\Domain\Exceptions\UserNotFound;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
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
}