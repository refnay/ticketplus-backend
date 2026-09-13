<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\DeleteDay\DeleteEventDayCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventDayDeleteController extends AbstractController
{
    public function delete(string $id, string $dayId, CommandBus $commandBus): JsonResponse
    {
        $commandBus->dispatch(new DeleteEventDayCommand($id, $dayId));

        return new JsonResponse([]);
    }
}
