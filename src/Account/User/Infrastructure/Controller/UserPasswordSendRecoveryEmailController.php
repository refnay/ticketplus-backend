<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\SendPasswordRecoveryEmail\SendUserPasswordRecoveryEmailCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordSendRecoveryEmailController extends AbstractController
{
    public function send(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = SendUserPasswordRecoveryEmailCommand::create($request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}