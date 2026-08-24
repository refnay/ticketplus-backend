<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Search\DiscountsResponse;
use App\Sale\Discount\Application\Search\SearchDiscountQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountSearchController extends AbstractController
{
    public function search(string $event, Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchDiscountQuery::fromQuery($event, $request->query->all());

        /** @var DiscountsResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
