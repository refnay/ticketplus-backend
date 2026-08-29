<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\ApprovedSalesByEventReport\OrderByEventResponse;
use App\Sale\Order\Application\ApprovedSalesByEventReport\ReportOrderApprovedSalesByEventQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderApprovedSalesByEventReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportOrderApprovedSalesByEventQuery::fromQuery($request->query->all());

        /** @var OrderByEventResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
