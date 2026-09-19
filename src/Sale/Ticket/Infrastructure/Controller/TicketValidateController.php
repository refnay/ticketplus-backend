<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\Validate\ValidateTicketCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketValidateController extends AbstractController
{
    public function validate(string $key, CommandBus $commandBus): JsonResponse
    {
        $command = ValidateTicketCommand::create($key);
        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}