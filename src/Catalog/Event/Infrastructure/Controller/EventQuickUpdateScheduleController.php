<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\QuickUpdate\Schedule\QuickUpdateScheduleEventCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventQuickUpdateScheduleController extends AbstractController
{
    public function update(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {
        $commandBus->dispatch(QuickUpdateScheduleEventCommand::create($id, $request->toArray()));

        return new JsonResponse([]);
    }
}
