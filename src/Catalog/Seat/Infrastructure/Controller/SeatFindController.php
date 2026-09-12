<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Find\FindSeatQuery;
use App\Catalog\Seat\Application\Find\SeatResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatFindController extends AbstractController
{
    public function find(string $id, QueryBus $queryBus): JsonResponse
    {
        $query = FindSeatQuery::create($id);

        /** @var SeatResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
