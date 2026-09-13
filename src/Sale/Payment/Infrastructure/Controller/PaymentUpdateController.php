<?php

namespace App\Sale\Payment\Infrastructure\Controller;

use App\Sale\Payment\Application\Confirm\ConfirmPaymentCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentConfirmController extends AbstractController
{
    public function confirm(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = ConfirmPaymentCommand::create($id, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
