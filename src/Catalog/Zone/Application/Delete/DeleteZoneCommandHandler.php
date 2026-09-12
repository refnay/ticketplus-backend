<?php

namespace App\Catalog\Zone\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class DeleteZoneCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneDeleter $deleter)
    {
    }

    public function __invoke(DeleteZoneCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->deleter->__invoke(
            ZoneId::fromString($command->id()),
            CompanyId::fromString($companyId),
        );
    }
}
