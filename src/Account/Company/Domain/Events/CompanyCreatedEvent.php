<?php

namespace App\Account\Company\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class CompanyCreatedEvent extends DomainEvent
{
    public function __construct(
        private string $user,
        private string $company,
    ) {
    }

    public function user(): string
    {
        return $this->user;
    }

    public function company(): string
    {
        return $this->company;
    }

    public function payload(): array
    {
        return [
            'user' => $this->user,
            'company' => $this->company,
        ];
    }
}
