<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Find\EventResponse;
use App\Catalog\Event\Application\Find\FindEventQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventFindController extends AbstractController
{
    public function find(string $id, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindEventQuery::create($id);
        $query->setSession($session);

        /** @var EventResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
