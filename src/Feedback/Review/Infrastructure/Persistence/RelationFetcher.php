<?php

namespace App\Feedback\Review\Infrastructure\Persistence;

use App\Feedback\Shared\Domain\EventId;
use App\Feedback\Shared\Domain\Exceptions\EventNotFound;
use App\Feedback\Shared\Domain\Exceptions\UserNotFound;
use App\Feedback\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\Event as EventEntity;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class RelationFetcher
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function event(EventId $id): EventEntity
    {
        try {
            return $this->entityManager->getReference(EventEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new EventNotFound();
        }
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