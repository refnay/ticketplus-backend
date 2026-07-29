<?php

namespace App\Catalog\Event\Application\Update;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Shared\Domain\CompanyId;

class UpdateEventCommandHandler
{
    public function __construct(private EventUpdater $updater)
    {
    }

    public function __invoke(UpdateEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventName::fromString($command->name()),
            EventDescription::fromString($command->description()),
            EventLocation::fromString($command->location()),
            EventCountry::fromString($command->country()),
            EventCity::fromString($command->city()),
            EventStatus::fromInt($command->status()),
            CategoryId::fromString($command->category()),
            CompanyId::fromString($command->session()->company()),
            $command->days(),
        );
    }
}