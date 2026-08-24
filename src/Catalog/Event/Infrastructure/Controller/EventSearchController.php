<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Search\EventsResponse;
use App\Catalog\Event\Application\Search\SearchEventQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventSearchController extends AbstractController
{
    public function search(Request $request, QueryBus $queryBus): JsonResponse
    {

        $query = SearchEventQuery::fromQuery($request->query->all());
        
        /** @var EventsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
