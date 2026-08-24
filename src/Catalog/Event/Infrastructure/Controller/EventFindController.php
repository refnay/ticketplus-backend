<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Find\EventResponse;
use App\Catalog\Event\Application\Find\FindEventQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventFindController extends AbstractController
{
    public function find(string $id, QueryBus $queryBus): JsonResponse
    {

        $query = FindEventQuery::create($id);

        /** @var EventResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
