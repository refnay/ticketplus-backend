<?php

namespace App\Account\CompanyMember\Infrastructure\Persistence;

use App\Account\Company\Domain\CompanyId;
use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberId;
use App\Account\CompanyMember\Domain\CompanyMemberRole;
use App\Account\CompanyMember\Domain\CompanyMemberStatus;
use App\Account\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\CompanyMember as CompanyMemberEntity;

class CompanyMemberMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(CompanyMember $companyMember): CompanyMemberEntity
    {
        $entity = new CompanyMemberEntity();
        
        $entity->setId($companyMember->id()->toUuid());
        $entity->setRole($companyMember->role()->value());
        $entity->setStatus($companyMember->status()->value());
        $entity->setCompany($this->fetcher->company($companyMember->companyId()));
        $entity->setMember($this->fetcher->user($companyMember->userId()));

        return $entity;
    }

    public function newDomain(CompanyMemberEntity $entity): CompanyMember
    {
        $companyMember = new CompanyMember(
            CompanyMemberId::fromString($entity->getId()),
            CompanyMemberRole::fromInt($entity->getRole()),
            CompanyMemberStatus::fromInt($entity->getStatus()),
            UserId::fromString($entity->getMember()->getId()),
            CompanyId::fromString($entity->getCompany()->getId()),
        );

        return $companyMember;
    }

    public function entityClass(): string
    {
        return CompanyMemberEntity::class;
    }
}