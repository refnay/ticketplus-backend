<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Search\EventsResponse;
use App\Catalog\Event\Application\Search\SearchEventQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventSearchController extends AbstractController
{
    public function search(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = SearchEventQuery::fromQuery($request->query->all());
        $query->setSession($session);
        
        /** @var EventsResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}