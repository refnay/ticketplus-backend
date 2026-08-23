<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Search\SearchSeatQuery;
use App\Catalog\Seat\Application\Search\SeatsResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatSearchController extends AbstractController
{
    public function search(string $event, string $day, string $zone, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = SearchSeatQuery::fromQuery($event, $day, $zone, $request->query->all());
        $query->setSession($session);

        /** @var SeatsResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
