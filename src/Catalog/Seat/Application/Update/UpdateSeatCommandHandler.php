<?php

namespace App\Catalog\Seat\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Shared\Domain\CompanyId;

class UpdateSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatUpdater $updater)
    {
    }

    public function __invoke(UpdateSeatCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->updater->__invoke(
            SeatId::fromString($command->id()),
            SeatCode::fromString($command->code()),
            SeatStatus::fromInt($command->status()),
            CompanyId::fromString($companyId),
        );
    }
}
