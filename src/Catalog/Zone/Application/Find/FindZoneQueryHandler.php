<?php

namespace App\Catalog\Zone\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class FindZoneQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneFinder $finder)
    {
    }

    public function __invoke(FindZoneQuery $query): ZoneResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->finder->__invoke(
            ZoneId::fromString($query->id()),
            CompanyId::fromString($companyId),
        );
    }
}
