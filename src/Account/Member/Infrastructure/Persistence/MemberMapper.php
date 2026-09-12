<?php

namespace App\Account\Member\Infrastructure\Persistence;

use App\Account\Company\Domain\CompanyId;
use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberId;
use App\Account\Member\Domain\MemberRole;
use App\Account\Member\Domain\MemberStatus;
use App\Account\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\CompanyMember as MemberEntity;

class MemberMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Member $member): MemberEntity
    {
        $entity = new MemberEntity();

        $entity->setId($member->id()->toUuid());
        $entity->setRole($member->role()->value());
        $entity->setStatus($member->status()->value());
        $entity->setCompany($this->fetcher->company($member->companyId()));
        $entity->setMember($this->fetcher->user($member->userId()));

        return $entity;
    }

    public function newDomain(MemberEntity $entity): Member
    {
        $member = new Member(
            MemberId::fromString($entity->getId()),
            MemberRole::fromInt($entity->getRole()),
            MemberStatus::fromInt($entity->getStatus()),
            UserId::fromString($entity->getMember()->getId()),
            CompanyId::fromString($entity->getCompany()->getId()),
        );

        return $member;
    }

    public function update(MemberEntity $entity, Member $member): void
    {
        $entity->setRole($member->role()->value());
        $entity->setStatus($member->status()->value());
    }

    public function entityClass(): string
    {
        return MemberEntity::class;
    }
}