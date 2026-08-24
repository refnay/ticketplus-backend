<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Update\UpdateDiscountCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountUpdateController extends AbstractController
{
    public function update(string $id, string $event, Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = UpdateDiscountCommand::create($id, $event, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
