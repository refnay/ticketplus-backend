<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Search\DiscountsResponse;
use App\Sale\Discount\Application\Search\SearchDiscountQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountSearchController extends AbstractController
{
    public function search(string $event, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = SearchDiscountQuery::fromQuery($event, $request->query->all());
        $query->setSession($session);

        /** @var DiscountsResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
