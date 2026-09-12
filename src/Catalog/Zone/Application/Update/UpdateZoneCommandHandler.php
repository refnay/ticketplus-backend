<?php

namespace App\Catalog\Zone\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;

class UpdateZoneCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneUpdater $updater)
    {
    }

    public function __invoke(UpdateZoneCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->updater->__invoke(
            ZoneId::fromString($command->id()),
            ZoneName::fromString($command->name()),
            ZonePrice::fromFloat($command->price()),
            ZoneQuantity::fromTotal($command->total()),
            ZoneHierarchy::fromInt($command->hierarchy()),
            ZoneNumberedSeating::fromBool($command->numberedSeating()),
            CompanyId::fromString($companyId),
        );
    }
}
