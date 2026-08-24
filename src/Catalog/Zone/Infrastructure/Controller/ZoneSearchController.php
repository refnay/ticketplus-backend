<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Search\SearchZoneQuery;
use App\Catalog\Zone\Application\Search\ZonesResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ZoneSearchController extends AbstractController
{
    public function search(string $event, string $day, Request $request, QueryBus $queryBus): JsonResponse
    {

        $query = SearchZoneQuery::fromQuery($event, $day, $request->query->all());

        /** @var ZonesResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
