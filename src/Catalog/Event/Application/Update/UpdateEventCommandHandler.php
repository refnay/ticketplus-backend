<?php

namespace App\Catalog\Event\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventCoordinates;
use App\Catalog\Event\Domain\EventCurrency;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Event\Domain\EventTaxRate;
use App\Catalog\Event\Domain\EventVenue;
use App\Catalog\Shared\Domain\CompanyId;

class UpdateEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater)
    {
    }

    public function __invoke(UpdateEventCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventName::fromString($command->name()),
            EventDescription::fromString($command->description()),
            EventVenue::fromString($command->venue()),
            EventCoordinates::fromArray($command->coordinates()),
            EventLocation::fromString($command->location()),
            EventCountry::fromString($command->country()),
            EventCity::fromString($command->city()),
            EventCurrency::fromString($command->currency()),
            EventTaxRate::fromFloat($command->taxRate()),
            EventStatus::fromInt($command->status()),
            CategoryId::fromString($command->category()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $command->days(),
        );
    }
}
