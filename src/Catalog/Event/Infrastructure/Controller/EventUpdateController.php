<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Update\UpdateEventCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventUpdateController extends AbstractController
{
    public function update(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {

        $data = $request->toArray();
        $data['id'] = $id;

        $command = UpdateEventCommand::create($data);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
