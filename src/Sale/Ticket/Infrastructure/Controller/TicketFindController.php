<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\FindByKey\FindTicketByKeyQuery;
use App\Sale\Ticket\Application\FindByKey\TicketByKeyResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketFindController extends AbstractController
{
    public function find(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = FindTicketByKeyQuery::fromQuery($request->query->all());

        /** @var TicketByKeyResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}