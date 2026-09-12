<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\SearchAvailable\AvailableZonesResponse;
use App\Catalog\Zone\Application\SearchAvailable\SearchAvailableZonesQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublicAvailableZoneSearchController extends AbstractController
{
    public function search(string $day, Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchAvailableZonesQuery::fromQuery($day, $request->query->all());

        /** @var AvailableZonesResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
