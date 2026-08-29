<?php

namespace App\Catalog\Seat\Infrastructure\Controller;

use App\Catalog\Seat\Application\BulkCreate\BulkCreateSeatCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SeatBulkCreateController extends AbstractController
{
    public function create(string $event, string $day, string $zone, Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = BulkCreateSeatCommand::create($event, $day, $zone, $request->toArray());
        
        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
