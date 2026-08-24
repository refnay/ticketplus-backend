<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Find\FindZoneQuery;
use App\Catalog\Zone\Application\Find\ZoneResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZoneFindController extends AbstractController
{
    public function find(string $id, string $event, string $day, QueryBus $queryBus): JsonResponse
    {
        $query = FindZoneQuery::create($id, $event, $day);

        /** @var ZoneResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
