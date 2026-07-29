<?php

namespace App\Account\Company\Infrastructure\Persistence;

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
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Domain\UserProfileImage;
use App\Account\User\Domain\UserStatus;
use App\Account\User\Domain\UserType;
use App\Shared\Infrastructure\Persistence\Entity\Company as CompanyEntity;

class CompanyMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Company $company): CompanyEntity
    {
        $entity = new CompanyEntity();
        
        $entity->setId($company->id()->toUuid());
        $entity->setName($company->name()->value());
        $entity->setDescription($company->description()->value());
        $entity->setLogo($company->logo()->value());
        $entity->setEmail($company->email()->value());
        $entity->setTelephone($company->telephone()->value());
        $entity->setWebSite($company->webSite()->value());
        $entity->setCountry($company->country()->value());
        $entity->setCity($company->city()->value());
        $entity->setDocumentType($company->document()->type());
        $entity->setDocumentNumber($company->document()->number());
        $entity->setLocation($company->location()->value());

        foreach ($company->members() as $user) {
            $entity->addMember($this->fetcher->user($user->id()));
        }

        return $entity;
    }

    public function newDomain(CompanyEntity $entity): Company
    {
        $company = new Company(
            CompanyId::fromString($entity->getId()),
            CompanyCity::fromString($entity->getCity()),
            CompanyCountry::fromString($entity->getCountry()),
            CompanyDocument::create($entity->getDocumentType(), $entity->getDocumentNumber()),
            CompanyEmail::fromString($entity->getEmail()),
            CompanyName::fromString($entity->getName()),
            CompanyStatus::fromInt($entity->getStatus()),
            CompanyLocation::fromString($entity->getLocation()),
            CompanyLogo::fromString($entity->getLogo()),
            CompanyDescription::fromString($entity->getDescription()),
            CompanyTelephone::fromString($entity->getTelephone()),
            CompanyWebSite::fromString($entity->getWebSite()),
        );

        foreach ($entity->getMembers() as $userEntity) {
            $member = new User(
                UserId::fromString($userEntity->getId()),
                UserEmail::fromString($userEntity->getEmail()),
                UserPassword::fromString($userEntity->getPassword()),
                UserBirthDate::fromDate($userEntity->getBirthDate()),
                UserCity::fromString($userEntity->getCity()),
                UserCountry::fromString($userEntity->getCountry()),
                UserDocument::create($userEntity->getDocumentType(), $userEntity->getDocumentNumber()),
                UserLastName::fromString($userEntity->getLastName()),
                UserMobile::fromString($userEntity->getMobile()),
                UserName::fromString($userEntity->getName()),
                UserProfileImage::fromString($userEntity->getProfileImage()),
                UserStatus::fromInt($userEntity->getStatus()),
                UserType::fromInt($userEntity->getType()),
            );

            $company->addMember($member);
        }

        return $company;
    }

    public function update(CompanyEntity $entity, Company $company): void
    {
        $entity->setName($company->name()->value());
        $entity->setDescription($company->description()->value());
        $entity->setLogo($company->logo()->value());
        $entity->setEmail($company->email()->value());
        $entity->setTelephone($company->telephone()->value());
        $entity->setWebSite($company->webSite()->value());
        $entity->setCountry($company->country()->value());
        $entity->setCity($company->city()->value());
        $entity->setDocumentType($company->document()->type());
        $entity->setDocumentNumber($company->document()->number());
        $entity->setLocation($company->location()->value());
    }

    public function entityClass(): string
    {
        return CompanyEntity::class;
    }
}