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
use App\Account\Company\Domain\CompanyMember;
use App\Account\Company\Domain\CompanyMemberId;
use App\Account\Company\Domain\CompanyMemberRole;
use App\Account\Company\Domain\CompanyMemberStatus;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyStatus;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\Company as CompanyEntity;
use App\Shared\Infrastructure\Persistence\Entity\CompanyMember as CompanyMemberEntity;

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
        $entity->setStatus($company->status()->value());

        foreach ($company->members() as $member) {
            $memberEntity = new CompanyMemberEntity();

            $memberEntity->setId($member->id()->toUuid());
            $memberEntity->setRole($member->role()->value());
            $memberEntity->setCompany($entity);
            $memberEntity->setMember($this->fetcher->user($member->userId()));

            $entity->addMember($memberEntity);
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

        foreach ($entity->getMembers() as $memberEntity) {
            $member = new CompanyMember(
                CompanyMemberId::fromString($memberEntity->getId()),
                CompanyMemberRole::fromInt($memberEntity->getRole()),
                CompanyMemberStatus::fromInt($memberEntity->getStatus()),
                UserId::fromString($memberEntity->getMember()->getId()),
                $company->id(),
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