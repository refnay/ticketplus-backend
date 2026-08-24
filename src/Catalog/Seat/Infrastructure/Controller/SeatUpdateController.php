<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\Update\UpdateSeatCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatUpdateController extends AbstractController
{
    public function update(string $id, string $event, string $day, string $zone, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = UpdateSeatCommand::create($id, $event, $day, $zone, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
