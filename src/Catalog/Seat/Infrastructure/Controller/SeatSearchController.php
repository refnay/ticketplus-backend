<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Search\SearchSeatQuery;
use App\Catalog\Seat\Application\Search\SeatsResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatSearchController extends AbstractController
{
    public function search(string $event, string $day, string $zone, Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchSeatQuery::fromQuery($event, $day, $zone, $request->query->all());

        /** @var SeatsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
