<?php

namespace App\Account\Member\Application\CompanyCreatedEvent;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Events\CompanyCreatedDomainEvent;
use App\Account\User\Domain\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event_bus')]
class CompanyCreatedEventSubscriber
{
    public function __construct(private MemberCreator $creator)
    {
    }

    public function __invoke(CompanyCreatedDomainEvent $event): void
    {
        $this->creator->__invoke(UserId::fromString($event->userId()), CompanyId::fromString($event->companyId()));
    }
}
