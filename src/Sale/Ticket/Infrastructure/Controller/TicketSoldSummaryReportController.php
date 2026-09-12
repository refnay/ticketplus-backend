<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\ReportSoldSummary\ReportTicketSoldSummaryQuery;
use App\Sale\Ticket\Application\ReportSoldSummary\TicketSummaryResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketSoldSummaryReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportTicketSoldSummaryQuery::fromQuery($request->query->all());

        /** @var TicketSummaryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
