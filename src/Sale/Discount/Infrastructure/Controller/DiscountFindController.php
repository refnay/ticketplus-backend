<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Find\DiscountResponse;
use App\Sale\Discount\Application\Find\FindDiscountQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DiscountFindController extends AbstractController
{
    public function find(string $id, string $event, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindDiscountQuery::create($id, $event);
        $query->setSession($session);

        /** @var DiscountResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
