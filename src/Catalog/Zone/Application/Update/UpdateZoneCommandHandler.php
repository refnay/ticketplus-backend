<?php

namespace App\Catalog\Zone\Application\Update;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneCurrency;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneTaxRate;

class UpdateZoneCommandHandler
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(UpdateZoneCommand $command): void
    {
        $this->updater->__invoke(
            ZoneId::fromString($command->id()),
            ZoneName::fromString($command->name()),
            ZoneCurrency::fromString($command->currency()),
            ZoneTaxRate::fromFloat($command->taxRate()),
            ZonePrice::fromFloat($command->price()),
            ZoneQuantity::create($command->totalQuantity(), $command->soldQuantity()),
            ZoneHierarchy::fromInt($command->hierarchy()),
            ZoneNumberedSeating::fromBool($command->numberedSeating()),
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}