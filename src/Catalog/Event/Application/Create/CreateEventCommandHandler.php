<?php

namespace App\Catalog\Event\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventCurrency;
use App\Catalog\Event\Domain\EventDescription;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventName;
use App\Catalog\Event\Domain\EventTaxRate;
use App\Catalog\Shared\Domain\CompanyId;

class CreateEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventCreator $creator)
    {
    }

    public function __invoke(CreateEventCommand $command): string
    {
        $this->authorization->requireAllPermissions();

        return $this->creator->__invoke(
            EventName::fromString($command->name()),
            EventDescription::fromString($command->description()),
            EventLocation::fromString($command->location()),
            EventCountry::fromString($command->country()),
            EventCity::fromString($command->city()),
            EventCurrency::fromString($command->currency()),
            EventTaxRate::fromFloat($command->taxRate()),
            CategoryId::fromString($command->category()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $command->days(),
        );
    }
}