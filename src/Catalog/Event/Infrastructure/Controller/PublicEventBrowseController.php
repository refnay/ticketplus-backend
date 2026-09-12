<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\BrowsePublished\BrowsePublishedEventsQuery;
use App\Catalog\Event\Application\BrowsePublished\PublishedEventsResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PublicEventBrowseController extends AbstractController
{
    public function browse(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = BrowsePublishedEventsQuery::fromQuery($request->query->all());

        /** @var PublishedEventsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
