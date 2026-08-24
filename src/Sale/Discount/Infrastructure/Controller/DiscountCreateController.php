<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Create\CreateDiscountCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountCreateController extends AbstractController
{
    public function create(string $event, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = CreateDiscountCommand::create($event, $request->toArray());
        $command->setSession($session);

        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
