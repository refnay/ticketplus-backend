<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Update\UpdateDiscountCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountUpdateController extends AbstractController
{
    public function update(string $id, string $event, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = UpdateDiscountCommand::create($id, $event, $request->toArray());
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
