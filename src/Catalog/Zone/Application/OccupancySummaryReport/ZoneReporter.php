<?php

namespace App\Catalog\Zone\Application\OccupancySummaryReport;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneReporter
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(CompanyId $companyId): ZoneSummaryResponse
    {
        $summary = $this->repository->occupancySummary($companyId);
        $total = $summary['total'];
        $sold = $summary['sold'];
        $reserved = $summary['reserved'];

        return new ZoneSummaryResponse(
            $total,
            $sold,
            $reserved,
            max(0, $total - $sold - $reserved),
            $total === 0 ? 0.00 : round($sold / $total, 2),
        );
    }
}
