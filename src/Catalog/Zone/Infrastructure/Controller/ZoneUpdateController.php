<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Update\UpdateZoneCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ZoneUpdateController extends AbstractController
{
    public function update(string $id, string $event, string $day, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = UpdateZoneCommand::create($id, $event, $day, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
