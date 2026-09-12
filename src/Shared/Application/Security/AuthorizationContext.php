<?php

namespace App\Shared\Application\Security;

use App\Account\Member\Domain\MemberRoleList;
use App\Account\Member\Domain\MemberStatusList;
use App\Shared\Application\Security\Exception\AuthenticationRequired;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\CompanyOwnerRequired;
use App\Shared\Application\Security\Exception\MemberRequired;

final readonly class AuthorizationContext
{
    public function __construct(private CurrentActor $actor)
    {
    }

    public function userId(): string
    {
        $userId = $this->actor->userId();
        if (is_null($userId)) {
            throw new AuthenticationRequired();
        }

        return $userId;
    }

    public function companyId(): string
    {
        $companyId = $this->actor->companyId();
        if (is_null($companyId)) {
            throw new CompanyRequired();
        }

        if (
            is_null($this->actor->memberId())
            || $this->actor->memberStatus() !== MemberStatusList::ACTIVE->value
        ) {
            throw new MemberRequired();
        }

        return $companyId;
    }

    public function memberId(): string
    {
        $this->companyId();

        $memberId = $this->actor->memberId();
        if (is_null($memberId)) {
            throw new MemberRequired();
        }

        return $memberId;
    }

    public function ownerCompanyId(): string
    {
        $companyId = $this->companyId();

        if ($this->actor->memberRole() !== MemberRoleList::OWNER->value) {
            throw new CompanyOwnerRequired();
        }

        return $companyId;
    }
}
