<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\Validate\ValidateTicketCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketValidateController extends AbstractController
{
    public function validate(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = ValidateTicketCommand::create($request->toArray());
        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
