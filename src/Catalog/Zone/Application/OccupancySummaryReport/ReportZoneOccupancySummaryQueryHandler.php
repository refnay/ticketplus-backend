<?php

namespace App\Catalog\Zone\Application\OccupancySummaryReport;

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
        $this->authorization->requireAllPermissions();

        return $this->reporter->__invoke(
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
