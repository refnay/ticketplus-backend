<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Create\CreateEventCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventCreateController extends AbstractController
{
    public function create(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = CreateEventCommand::create($request->toArray());

        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
