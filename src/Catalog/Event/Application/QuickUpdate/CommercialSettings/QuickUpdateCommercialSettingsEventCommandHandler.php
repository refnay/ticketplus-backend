<?php

namespace App\Catalog\Event\Application\QuickUpdate\CommercialSettings;

use App\Catalog\Event\Domain\EventCurrency;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventOrderLimit;
use App\Catalog\Event\Domain\EventTaxRate;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class QuickUpdateCommercialSettingsEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventUpdater $updater) {}

    public function __invoke(QuickUpdateCommercialSettingsEventCommand $command): void
    {
        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventCurrency::fromString($command->currency()),
            EventTaxRate::fromFloat($command->taxRate()),
            EventOrderLimit::fromInt($command->orderLimit()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
