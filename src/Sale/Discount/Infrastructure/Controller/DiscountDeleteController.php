<?php

namespace App\Sale\Discount\Infrastructure\Controller;

use App\Sale\Discount\Application\Delete\DeleteDiscountCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DiscountDeleteController extends AbstractController
{
    public function delete(string $id, string $event, CommandBus $commandBus): JsonResponse
    {
        $command = DeleteDiscountCommand::create($id, $event);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
