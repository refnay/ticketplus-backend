<?php

namespace App\Account\Company\Infrastructure\Persistence;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyMember;
use App\Account\Company\Domain\CompanyMemberId;
use App\Account\Company\Domain\CompanyMemberRole;
use App\Account\Company\Domain\CompanyMemberStatus;
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