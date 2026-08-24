<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Find\FindSeatQuery;
use App\Catalog\Seat\Application\Find\SeatResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatFindController extends AbstractController
{
    public function find(string $id, string $event, string $day, string $zone, QueryBus $queryBus): JsonResponse
    {
        $query = FindSeatQuery::create($id, $event, $day, $zone);

        /** @var SeatResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
