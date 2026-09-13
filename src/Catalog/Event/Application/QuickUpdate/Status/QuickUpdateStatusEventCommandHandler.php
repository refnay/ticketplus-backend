<?php

namespace App\Catalog\Event\Application\QuickUpdate\Status;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class QuickUpdateStatusEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater) {}

    public function __invoke(QuickUpdateStatusEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventStatus::fromInt($command->status()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
