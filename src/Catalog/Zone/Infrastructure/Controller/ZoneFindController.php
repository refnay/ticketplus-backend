<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Find\FindZoneQuery;
use App\Catalog\Zone\Application\Find\ZoneResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZoneFindController extends AbstractController
{
    public function find(string $id, string $event, string $day, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindZoneQuery::create($id, $event, $day);
        $query->setSession($session);

        /** @var ZoneResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
