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