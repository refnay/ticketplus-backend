<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Create\CreateDiscountCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountCreateController extends AbstractController
{
    public function create(string $event, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = CreateDiscountCommand::create($event, $request->toArray());

        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
