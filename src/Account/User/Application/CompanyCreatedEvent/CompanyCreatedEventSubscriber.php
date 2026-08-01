<?php

namespace App\Account\User\Application\CompanyCreatedEvent;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Events\CompanyCreatedEvent;
use App\Account\User\Domain\UserId;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompanyCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserUpdater $updater)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [CompanyCreatedEvent::class => 'handle'];
    }

    public function handle(CompanyCreatedEvent $event): void
    {
        $this->updater->__invoke(UserId::fromString($event->user()), CompanyId::fromString($event->company()));
    }
}
