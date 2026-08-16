<?php

namespace App\Account\Company\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;

class CompanyCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        private string $userId,
        private string $companyId,
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function companyId(): string
    {
        return $this->companyId;
    }

    public function payload(): array
    {
        return [
            'userId' => $this->userId,
            'companyId' => $this->companyId,
        ];
    }
}
