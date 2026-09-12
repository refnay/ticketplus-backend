<?php

namespace App\Catalog\Seat\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Shared\Domain\CompanyId;

class DeleteSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatDeleter $deleter)
    {
    }

    public function __invoke(DeleteSeatCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->deleter->__invoke(
            SeatId::fromString($command->id()),
            CompanyId::fromString($companyId),
        );
    }
}
