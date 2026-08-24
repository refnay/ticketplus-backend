<?php

namespace App\Shared\Domain\Session;

use App\Shared\Domain\Enums\MemberStatusList;
use App\Shared\Domain\Enums\UserStatusList;
use App\Shared\Domain\Enums\UserTypeList;
use App\Shared\Domain\Exceptions\MemberRequired;
use App\Shared\Domain\Exceptions\CompanyRequired;
use App\Shared\Domain\Exceptions\MemberNotAllowed;
use App\Shared\Domain\Exceptions\UserNotAllowed;
use App\Shared\Domain\Utils\IntegerHelper;

final class Session
{
    public function __construct(private SessionProvider $provider)
    {
    }

    public function user(): string
    {
        return $this->provider->user();
    }

    public function company(): ?string
    {
        return $this->provider->company();
    }

    public function member(): ?string
    {
        return $this->provider->member();
    }

    public function allPermissions(): void
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
        if (is_null($this->company())) {
            throw new CompanyRequired();
        }

        return;
    }

    public function memberRequired(): void
    {
        if (is_null($this->member())) {
            throw new MemberRequired();
        }

        return;
    }

    public function userTypeAllowed(): void
    {
        if (!IntegerHelper::isEqual(UserTypeList::WORKER->value, $this->provider->userType())) {
            throw new UserNotAllowed();
        }

        return;
    }

    public function userStatusAllowed(): void
    {
        if (in_array($this->provider->userStatus(), UserStatusList::blocked(), true)) {
            throw new UserNotAllowed();
        }

        return;
    }

    public function memberStatusAllowed(): void
    {
        if (IntegerHelper::isEqual($this->provider->memberStatus(), MemberStatusList::INACTIVE->value)) {
            throw new MemberNotAllowed();
        }

        return;
    }
} 
