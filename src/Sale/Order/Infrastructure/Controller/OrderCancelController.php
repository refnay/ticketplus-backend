<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\Cancel\CancelOrderCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderCancelController extends AbstractController
{
    public function cancel(string $id, CommandBus $commandBus): JsonResponse
    {

        $command = CancelOrderCommand::create($id);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
