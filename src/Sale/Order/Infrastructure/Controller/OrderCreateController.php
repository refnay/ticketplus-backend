<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\Create\CreateOrderCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderCreateController extends AbstractController
{
    public function create(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = CreateOrderCommand::create($request->toArray());
        $command->setSession($session);
        
        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
