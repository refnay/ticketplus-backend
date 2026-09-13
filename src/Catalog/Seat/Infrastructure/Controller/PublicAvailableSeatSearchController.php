<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\SearchAvailable\SeatsResponse;
use App\Catalog\Seat\Application\SearchAvailable\SearchAvailableSeatQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublicAvailableSeatSearchController extends AbstractController
{
    public function search(
        string $zone,
        Request $request,
        QueryBus $queryBus,
    ): JsonResponse {
        $query = SearchAvailableSeatQuery::fromQuery($zone, $request->query->all());

        /** @var SeatsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
