<?php

namespace App\Identity\User\Infrastructure\Persistence;

use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserBirthDate;
use App\Identity\User\Domain\UserCity;
use App\Identity\User\Domain\UserCountry;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserLastName;
use App\Identity\User\Domain\UserMobile;
use App\Identity\User\Domain\UserName;
use App\Identity\User\Domain\UserPassword;
use App\Identity\User\Domain\UserProfileImage;
use App\Identity\User\Domain\UserStatus;
use App\Identity\User\Domain\UserType;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;

class UserMapper
{
    public function newEntity(User $user): UserEntity
    {
        $entity = new UserEntity();
        
        $entity->setId($user->id()->toUuid());
        $entity->setEmail($user->email()->value());
        $entity->setPassword($user->password()->value());
        $entity->setName($user->name()->value());
        $entity->setLastName($user->lastName()->value());
        $entity->setBirthDate($user->birthDate()->toDateTime());
        $entity->setCity($user->city()->value());
        $entity->setCountry($user->country()->value());
        $entity->setMobile($user->mobile()->value());
        $entity->setProfileImage($user->profileImage()->value());
        $entity->setDocumentType($user->document()->type());
        $entity->setDocumentNumber($user->document()->number());
        $entity->setType($user->type()->value());
        $entity->setStatus($user->status()->value());
        $entity->setRoles(['ROLE_USER']);

        return $entity;
    }

    public function newDomain(UserEntity $entity): User
    {
        $user = new User(
            UserId::fromString($entity->getId()),
            UserEmail::fromString($entity->getEmail()),
            UserPassword::fromString($entity->getPassword()),
            UserBirthDate::fromDate($entity->getBirthDate()),
            UserCity::fromString($entity->getCity()),
            UserCountry::fromString($entity->getCountry()),
            UserDocument::create($entity->getDocumentType(), $entity->getDocumentNumber()),
            UserLastName::fromString($entity->getLastName()),
            UserMobile::fromString($entity->getMobile()),
            UserName::fromString($entity->getName()),
            UserProfileImage::fromString($entity->getProfileImage()),
            UserStatus::fromInt($entity->getStatus()),
            UserType::fromInt($entity->getType()),
        );

        return $user;
    }

    public function update(UserEntity $entity, User $user): void
    {
        $entity->setName($user->name()->value());
        $entity->setLastName($user->lastName()->value());
        $entity->setBirthDate($user->birthDate()->toDateTime());
        $entity->setCity($user->city()->value());
        $entity->setCountry($user->country()->value());
        $entity->setMobile($user->mobile()->value());
        $entity->setDocumentType($user->document()->type());
        $entity->setDocumentNumber($user->document()->number());
    }

    public function updatePassword(UserEntity $entity, UserPassword $newPassword): void
    {
        $entity->setPassword($newPassword->value());
    }

    public function entityClass(): string
    {
        return UserEntity::class;
    }
}