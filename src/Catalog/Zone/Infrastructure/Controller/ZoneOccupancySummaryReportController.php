<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\ReportOccupancySummary\ReportZoneOccupancySummaryQuery;
use App\Catalog\Zone\Application\ReportOccupancySummary\ZoneSummaryResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZoneOccupancySummaryReportController extends AbstractController
{
    public function report(QueryBus $queryBus): JsonResponse
    {
        /** @var ZoneSummaryResponse $response */
        $response = $queryBus->ask(new ReportZoneOccupancySummaryQuery());

        return new JsonResponse($response->jsonSerialize());
    }
}
