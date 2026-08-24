<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Delete\DeleteSeatCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeatDeleteController extends AbstractController
{
    public function delete(string $id, string $event, string $day, string $zone, CommandBus $commandBus): JsonResponse
    {

        $command = DeleteSeatCommand::create($id, $event, $day, $zone);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
