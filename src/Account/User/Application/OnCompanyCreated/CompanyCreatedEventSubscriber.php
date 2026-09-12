<?php

namespace App\Account\User\Application\OnCompanyCreated;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Events\CompanyCreatedDomainEvent;
use App\Account\User\Domain\UserId;

class CompanyCreatedEventSubscriber
{
    public function __construct(private UserUpdater $updater)
    {
    }

    public function __invoke(CompanyCreatedDomainEvent $event): void
    {
        $this->updater->__invoke(UserId::fromString($event->userId()), CompanyId::fromString($event->companyId()));
    }
}
