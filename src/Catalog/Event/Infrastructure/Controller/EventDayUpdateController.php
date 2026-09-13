<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\UpdateDay\UpdateEventDayCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventDayUpdateController extends AbstractController
{
    public function update(string $id, string $dayId, Request $request, CommandBus $commandBus): JsonResponse
    {
        $commandBus->dispatch(UpdateEventDayCommand::create($id, $dayId, $request->toArray()));

        return new JsonResponse([]);
    }
}
