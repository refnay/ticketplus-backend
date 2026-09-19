<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\Search\SearchTicketQuery;
use App\Sale\Ticket\Application\Search\TicketsResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketSearchController extends AbstractController
{
    public function search(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchTicketQuery::fromQuery($request->query->all());

        /** @var TicketsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}