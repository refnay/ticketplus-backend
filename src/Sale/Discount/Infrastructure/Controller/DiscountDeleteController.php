<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Delete\DeleteDiscountCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DiscountDeleteController extends AbstractController
{
    public function delete(string $id, string $event, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = DeleteDiscountCommand::create($id, $event);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
