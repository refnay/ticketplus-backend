<?php

namespace App\Catalog\Seat\Application\BulkCreate;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class BulkCreateSeatCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatBulkCreator $creator)
    {
    }

    public function __invoke(BulkCreateSeatCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->creator->__invoke(
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $command->seats(),
        );
    }
}