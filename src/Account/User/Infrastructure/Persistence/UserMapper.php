<?php

namespace App\Account\User\Infrastructure\Persistence;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyLogo;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyStatus;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberCurrent;
use App\Account\CompanyMember\Domain\CompanyMemberId;
use App\Account\CompanyMember\Domain\CompanyMemberRole;
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;
use App\Account\User\Domain\UserOwner;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Domain\UserProfileImage;
use App\Account\User\Domain\UserStatus;
use App\Account\User\Domain\UserType;
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
            UserOwner::fromBool($entity->isOwner()),
            UserCurrentCompany::fromString($entity->getCurrentCompany())
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
        $entity->setProfileImage($user->profileImage()->value());
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
