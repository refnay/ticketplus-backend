<?php

namespace App\Catalog\Event\Application\QuickUpdate\Schedule;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class QuickUpdateScheduleEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater) {}

    public function __invoke(QuickUpdateScheduleEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            CompanyId::fromString($this->authorization->companyId()),
            $command->days(),
        );
    }
}
