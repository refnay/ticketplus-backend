<?php

namespace App\Catalog\Event\Application\QuickUpdate\General;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class QuickUpdateGeneralEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater) {}

    public function __invoke(QuickUpdateGeneralEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventName::fromString($command->name()),
            EventDescription::fromString($command->description()),
            CategoryId::fromString($command->category()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
