<?php

namespace App\Sale\Order\Infrastructure\Controller;

use App\Sale\Order\Application\Create\CreateOrderCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderCreateController extends AbstractController
{
    public function create(Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = CreateOrderCommand::create($request->toArray());
        
        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
