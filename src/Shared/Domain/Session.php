<?php

namespace App\Shared\Domain;

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

    public function allowed(): void
    {
        if (is_null($this->company())) {
            throw new CompanyRequired();
        }

        $this->typeAllowed();

        return;
    }

    public function typeAllowed(): void
    {
        if (!IntegerHelper::isEqual(UserTypesList::WORKER->value, $this->provider->type())) {
            throw new UserNotAllowed();
        }

        return;
    }
} 