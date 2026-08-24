<?php

namespace App\Shared\Application\Security;

use App\Shared\Domain\Enums\UserTypeList;
use App\Shared\Application\Security\Exception\MemberRequired;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\UserNotAllowed;
use App\Shared\Domain\Utils\IntegerHelper as Integer;

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
        $this->requireCompanyId();
        $this->requireMemberId();
        
        $this->workerAllowed();

        return;
    }

    public function workerAllowed(): void
    {
        if (!Integer::equals(UserTypeList::WORKER->value, $this->actor->userType())) {
            throw new UserNotAllowed();
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
