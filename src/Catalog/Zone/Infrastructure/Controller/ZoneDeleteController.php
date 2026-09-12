<?php

namespace App\Catalog\Zone\Infrastructure\Controller;

use App\Catalog\Zone\Application\Delete\DeleteZoneCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ZoneDeleteController extends AbstractController
{
    public function delete(string $id, CommandBus $commandBus): JsonResponse
    {
        $command = DeleteZoneCommand::create($id);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
