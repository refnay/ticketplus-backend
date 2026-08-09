<?php

namespace App\Account\CompanyMember\Application\CompanyCreatedEvent;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Events\CompanyCreatedEvent;
use App\Account\User\Domain\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CompanyCreatedEventSubscriber
{
    public function __construct(private CompanyMemberCreator $creator)
    {
    }

    public function __invoke(CompanyCreatedEvent $event): void
    {
        $this->creator->__invoke(UserId::fromString($event->user()), CompanyId::fromString($event->company()));
    }
}
