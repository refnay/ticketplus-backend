<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Update\UpdateSeatCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatUpdateController extends AbstractController
{
    public function update(string $id, string $event, string $day, string $zone, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = UpdateSeatCommand::create($id, $event, $day, $zone, $request->toArray());
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
