<?php

namespace App\Sale\Payment\Infrastructure\Controller;

use App\Sale\Payment\Application\Confirm\UpdatePaymentCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentUpdateController extends AbstractController
{
    public function update(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = UpdatePaymentCommand::create($id, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
