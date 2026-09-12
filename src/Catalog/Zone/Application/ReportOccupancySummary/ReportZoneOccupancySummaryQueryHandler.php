<?php

namespace App\Catalog\Zone\Application\ReportOccupancySummary;

use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportZoneOccupancySummaryQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private ZoneReporter $reporter,
    ) {
    }

    public function __invoke(ReportZoneOccupancySummaryQuery $query): ZoneSummaryResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->reporter->__invoke(
            CompanyId::fromString($companyId),
        );
    }
}
