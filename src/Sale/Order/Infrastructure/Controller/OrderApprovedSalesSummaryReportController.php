<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\ReportApprovedSalesSummary\OrderSummaryResponse;
use App\Sale\Order\Application\ReportApprovedSalesSummary\ReportOrderApprovedSalesSummaryQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderApprovedSalesSummaryReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportOrderApprovedSalesSummaryQuery::fromQuery($request->query->all());

        /** @var OrderSummaryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
