<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Choose\ChooseEventsQuery;
use App\Catalog\Event\Application\Choose\EventChoicesResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventChooseController extends AbstractController
{
    public function choose(QueryBus $queryBus): JsonResponse
    {
        /** @var EventChoicesResponse $response */
        $response = $queryBus->ask(ChooseEventsQuery::create());

        return new JsonResponse($response->jsonSerialize());
    }
}
