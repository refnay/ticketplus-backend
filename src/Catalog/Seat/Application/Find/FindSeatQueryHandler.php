<?php

namespace App\Catalog\Seat\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class FindSeatQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatFinder $finder)
    {
    }

    public function __invoke(FindSeatQuery $query): SeatResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->finder->__invoke(
            SeatId::fromString($query->id()),
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            ZoneId::fromString($query->zone()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}