<?php

namespace App\Catalog\Seat\Application\BulkCreate;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class BulkCreateSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatBulkCreator $creator)
    {
    }

    public function __invoke(BulkCreateSeatCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->creator->__invoke(
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($companyId),
            $command->seats(),
        );
    }
}
