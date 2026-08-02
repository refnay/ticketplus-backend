<?php

namespace App\Shared\Domain;

use App\Account\User\Domain\UserStatusList;
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

    public function allPermissions(): void
    {
        $this->companyRequired();
        
        $this->typeAllowed();
        $this->statusAllowed();

        return;
    }

    public function companyRequired(): void
    {
        if (is_null($this->company())) {
            throw new CompanyRequired();
        }

        return;
    }

    public function typeAllowed(): void
    {
        if (!IntegerHelper::isEqual(UserTypesList::WORKER->value, $this->provider->type())) {
            throw new UserNotAllowed();
        }

        return;
    }

    public function statusAllowed(): void
    {
        if (in_array($this->provider->status(), UserStatusList::blocked(), true)) {
            throw new UserNotAllowed();
        }

        return;
    }
} 