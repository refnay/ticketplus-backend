<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Delete\DeleteEventCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventDeleteController extends AbstractController
{
    public function delete(string $id, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = DeleteEventCommand::create($id);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
