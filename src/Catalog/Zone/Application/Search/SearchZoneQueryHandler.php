<?php

namespace App\Catalog\Zone\Application\Search;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class SearchZoneQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneSearcher $searcher)
    {
    }

    public function __invoke(SearchZoneQuery $query): ZonesResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->searcher->__invoke(
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
