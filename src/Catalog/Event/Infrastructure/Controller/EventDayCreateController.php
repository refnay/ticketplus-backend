<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\CreateDay\CreateEventDayCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventDayCreateController extends AbstractController
{
    public function create(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {
        $dayId = $commandBus->dispatch(CreateEventDayCommand::create($id, $request->toArray()));

        return new JsonResponse(['id' => $dayId], JsonResponse::HTTP_CREATED);
    }
}
