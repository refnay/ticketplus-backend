<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\PaidCountSummaryReport\OrderSummaryResponse;
use App\Sale\Order\Application\PaidCountSummaryReport\ReportOrderPaidCountSummaryQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderPaidCountSummaryReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportOrderPaidCountSummaryQuery::fromQuery($request->query->all());

        /** @var OrderSummaryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
