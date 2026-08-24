<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Delete\DeleteEventCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventDeleteController extends AbstractController
{
    public function delete(string $id, CommandBus $commandBus): JsonResponse
    {
        $command = DeleteEventCommand::create($id);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
