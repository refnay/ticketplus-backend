<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\ApprovedSalesEvolutionReport\OrderEvolutionResponse;
use App\Sale\Order\Application\ApprovedSalesEvolutionReport\ReportOrderApprovedSalesEvolutionQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderApprovedSalesEvolutionReportController extends AbstractController
{
    public function report(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = ReportOrderApprovedSalesEvolutionQuery::fromQuery($request->query->all());

        /** @var OrderEvolutionResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
