<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Find\DiscountResponse;
use App\Sale\Discount\Application\Find\FindDiscountQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DiscountFindController extends AbstractController
{
    public function find(string $id, string $event, QueryBus $queryBus): JsonResponse
    {
        $query = FindDiscountQuery::create($id, $event);

        /** @var DiscountResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
