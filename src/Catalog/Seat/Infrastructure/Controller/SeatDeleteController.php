<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Delete\DeleteSeatCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatDeleteController extends AbstractController
{
    public function delete(string $id, string $event, string $day, string $zone, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = DeleteSeatCommand::create($id, $event, $day, $zone);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
