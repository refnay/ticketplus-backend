<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\PaidSummaryReport\SummaryResponse;
use App\Sale\Order\Application\PaidSummaryReport\ReportOrderPaidSummaryQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderPaidSummaryReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportOrderPaidSummaryQuery::fromQuery($request->query->all());

        /** @var SummaryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
