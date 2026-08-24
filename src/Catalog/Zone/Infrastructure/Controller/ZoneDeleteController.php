<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Delete\DeleteZoneCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZoneDeleteController extends AbstractController
{
    public function delete(string $id, string $event, string $day, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = DeleteZoneCommand::create($id, $event, $day);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
