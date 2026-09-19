<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\ReportValidationSummary\ReportTicketValidationSummaryQuery;
use App\Sale\Ticket\Application\ReportValidationSummary\TicketValidationSummaryResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketValidationSummaryReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportTicketValidationSummaryQuery::fromQuery($request->query->all());

        /** @var TicketValidationSummaryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
