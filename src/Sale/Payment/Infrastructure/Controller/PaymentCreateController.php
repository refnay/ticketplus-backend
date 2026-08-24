<?php

namespace App\Sale\Payment\Infrastructure\Controller;

use App\Sale\Payment\Application\Create\CreatePaymentCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentCreateController extends AbstractController
{
    public function create(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = CreatePaymentCommand::create($request->toArray());
        
        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
