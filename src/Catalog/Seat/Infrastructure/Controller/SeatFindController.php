<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Find\FindSeatQuery;
use App\Catalog\Seat\Application\Find\SeatResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatFindController extends AbstractController
{
    public function find(string $id, string $event, string $day, string $zone, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindSeatQuery::create($id, $event, $day, $zone);
        $query->setSession($session);

        /** @var SeatResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
