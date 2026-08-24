<?php

namespace App\Shared\Application\Security;

use App\Shared\Domain\Enums\MemberStatusList;
use App\Shared\Domain\Enums\UserStatusList;
use App\Shared\Domain\Enums\UserTypeList;
use App\Shared\Application\Security\Exception\MemberRequired;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\MemberNotAllowed;
use App\Shared\Application\Security\Exception\UserNotAllowed;
use App\Shared\Domain\Utils\IntegerHelper;

final readonly class AuthorizationContext
{
    public function __construct(private CurrentActor $actor)
    {
    }

    public function userId(): string
    {
        return $this->actor->userId();
    }

    public function companyId(): ?string
    {
        return $this->actor->companyId();
    }

    public function memberId(): ?string
    {
        return $this->actor->memberId();
    }

    public function requireAllPermissions(): void
    {
        $this->companyRequired();
        $this->memberRequired();
        
        $this->userTypeAllowed();
        $this->userStatusAllowed();

        $this->memberStatusAllowed();

        return;
    }

    public function companyRequired(): void
    {
        if (is_null($this->companyId())) {
            throw new CompanyRequired();
        }

        return;
    }

    public function memberRequired(): void
    {
        if (is_null($this->memberId())) {
            throw new MemberRequired();
        }

        return;
    }

    public function userTypeAllowed(): void
    {
        if (!IntegerHelper::isEqual(UserTypeList::WORKER->value, $this->actor->userType())) {
            throw new UserNotAllowed();
        }

        return;
    }

    public function userStatusAllowed(): void
    {
        if (in_array($this->actor->userStatus(), UserStatusList::blocked(), true)) {
            throw new UserNotAllowed();
        }

        return;
    }

    public function memberStatusAllowed(): void
    {
        if (IntegerHelper::isEqual($this->actor->memberStatus(), MemberStatusList::INACTIVE->value)) {
            throw new MemberNotAllowed();
        }

        return;
    }

    public function requireCompanyId(): string
    {
        $companyId = $this->companyId();
        if (is_null($companyId)) {
            throw new CompanyRequired();
        }

        return $companyId;
    }

    public function requireMemberId(): string
    {
        $memberId = $this->memberId();
        if (is_null($memberId)) {
            throw new MemberRequired();
        }

        return $memberId;
    }
}
