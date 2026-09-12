<?php

namespace App\Account\Member\Infrastructure\Persistence;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Exceptions\CompanyNotFound;
use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\Company as CompanyEntity;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
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

    public function user(UserId $id): UserEntity
    {
        try {
            return $this->entityManager->getReference(UserEntity::class, $id->toUuid());
        } catch (Throwable) {
            throw new UserNotFound();
        }
    }
}