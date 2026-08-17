<?php

namespace App\Sale\Payment\Infrastructure\Controller;

use App\Sale\Payment\Application\Update\UpdatePaymentCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentUpdateController extends AbstractController
{
    public function update(string $id, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = UpdatePaymentCommand::create($id, $request->toArray());
        $command->setSession($session);
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}