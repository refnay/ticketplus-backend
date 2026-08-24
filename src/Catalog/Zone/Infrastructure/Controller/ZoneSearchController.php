<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Search\SearchZoneQuery;
use App\Catalog\Zone\Application\Search\ZonesResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ZoneSearchController extends AbstractController
{
    public function search(string $event, string $day, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = SearchZoneQuery::fromQuery($event, $day, $request->query->all());
        $query->setSession($session);

        /** @var ZonesResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
