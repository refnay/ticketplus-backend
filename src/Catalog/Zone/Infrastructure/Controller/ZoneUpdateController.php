<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Update\UpdateZoneCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ZoneUpdateController extends AbstractController
{
    public function update(string $id, string $event, string $day, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = UpdateZoneCommand::create($id, $event, $day, $request->toArray());
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
