<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\Create\CreateEventCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventCreateController extends AbstractController
{
    public function create(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = CreateEventCommand::create($request->toArray());
        $command->setSession($session);
        
        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}