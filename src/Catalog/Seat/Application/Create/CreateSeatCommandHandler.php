<?php

namespace App\Catalog\Seat\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class CreateSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatCreator $creator)
    {
    }

    public function __invoke(CreateSeatCommand $command): string
    {
        $companyId = $this->authorization->companyId();

        return $this->creator->__invoke(
            SeatCode::fromString($command->code()),
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($companyId),
        );
    }
}
