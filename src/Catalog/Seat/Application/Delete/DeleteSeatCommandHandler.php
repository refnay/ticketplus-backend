<?php

namespace App\Catalog\Seat\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class DeleteSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatDeleter $deleter)
    {
    }

    public function __invoke(DeleteSeatCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->deleter->__invoke(
            SeatId::fromString($command->id()),
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}