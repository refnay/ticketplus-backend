<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\ViewPublished\PublishedEventResponse;
use App\Catalog\Event\Application\ViewPublished\ViewPublishedEventQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PublicEventViewController extends AbstractController
{
    public function view(string $id, QueryBus $queryBus): JsonResponse
    {
        /** @var PublishedEventResponse $response */
        $response = $queryBus->ask(ViewPublishedEventQuery::create($id));

        return new JsonResponse($response->jsonSerialize());
    }
}
