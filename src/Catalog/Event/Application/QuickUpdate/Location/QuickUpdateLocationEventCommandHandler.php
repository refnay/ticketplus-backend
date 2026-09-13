<?php

namespace App\Catalog\Event\Application\QuickUpdate\Location;

use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCoordinates;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventVenue;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class QuickUpdateLocationEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater) {}

    public function __invoke(QuickUpdateLocationEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventVenue::fromString($command->venue()),
            EventLocation::fromString($command->location()),
            EventCountry::fromString($command->country()),
            EventCity::fromString($command->city()),
            EventCoordinates::fromArray($command->coordinates()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
