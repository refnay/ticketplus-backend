<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Create\CreateZoneCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ZoneCreateController extends AbstractController
{
    public function create(string $event, string $day, Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = CreateZoneCommand::create($event, $day, $request->toArray());
        
        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
