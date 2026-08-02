<?php

namespace App\Shared\Domain;

use App\Account\Company\Domain\CompanyMemberStatusList;
use App\Account\Company\Domain\Exceptions\CompanyMemberNotAllowed;
use App\Account\User\Domain\UserStatusList;
use App\Shared\Domain\Exceptions\CompanyMemberRequired;
use App\Shared\Domain\Exceptions\CompanyRequired;
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
        if (is_null($this->company())) {
            throw new CompanyMemberRequired();
        }

        return;
    }

    public function userTypeAllowed(): void
    {
        if (!IntegerHelper::isEqual(UserTypesList::WORKER->value, $this->provider->userType())) {
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
        if (!IntegerHelper::isEqual($this->provider->userStatus(), CompanyMemberStatusList::INACTIVE->value)) {
            throw new CompanyMemberNotAllowed();
        }

        return;
    }
} 