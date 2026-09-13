<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\BrowsePublished\BrowsePublishedEventQuery;
use App\Catalog\Event\Application\BrowsePublished\EventsResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublicEventBrowseController extends AbstractController
{
    public function browse(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = BrowsePublishedEventQuery::fromQuery($request->query->all());

        /** @var EventsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
