<?php

namespace App\Catalog\Event\Application\Create;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventStatus;
use App\Catalog\Shared\Domain\CompanyId;

class CreateEventCommandHandler
{
    public function __construct(private EventCreator $creator)
    {
    }

    public function __invoke(CreateEventCommand $command): string
    {
        return $this->creator->__invoke(
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