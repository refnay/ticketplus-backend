<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\Cancel\CancelOrderCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderCancelController extends AbstractController
{
    public function cancel(string $id, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = CancelOrderCommand::create($id);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
