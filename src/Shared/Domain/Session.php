<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Exceptions\CompanyRequired;

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

    public function ensureCompany(): void
    {
        if (!is_null($this->company())) {
            return;
        }

        throw new CompanyRequired();
    }
} 