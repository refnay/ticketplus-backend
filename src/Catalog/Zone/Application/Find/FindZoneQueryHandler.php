<?php

namespace App\Catalog\Zone\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class FindZoneQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneFinder $finder)
    {
    }

    public function __invoke(FindZoneQuery $query): ZoneResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->finder->__invoke(
            ZoneId::fromString($query->id()),
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}