<?php

namespace App\Identity\User\Infrastructure\Controller;

use App\Identity\User\Application\Recovery\SendEmail\SendUserPasswordRecoveryEmailCommand;
use App\Shared\Application\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordSendRecoveryEmailController extends AbstractController
{
    public function send(Request $request, MessageBus $messageBus): JsonResponse
    {
        $command = SendUserPasswordRecoveryEmailCommand::create($request->toArray());
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}