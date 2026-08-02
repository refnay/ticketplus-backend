<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Create\CreateSeatCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatCreateController extends AbstractController
{
    public function create(string $event, string $day, string $zone, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = CreateSeatCommand::create($event, $day, $zone, $request->toArray());
        $command->setSession($session);
        
        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}