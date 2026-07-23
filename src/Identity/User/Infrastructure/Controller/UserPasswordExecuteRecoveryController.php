<?php

namespace App\Identity\User\Infrastructure\Controller;

use App\Identity\User\Application\Recovery\Execute\ExecuteUserPasswordRecoveryCommand;
use App\Shared\Application\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordExecuteRecoveryController extends AbstractController
{
    public function execute(Request $request, MessageBus $messageBus): JsonResponse
    {
        $command = ExecuteUserPasswordRecoveryCommand::create($request->toArray());
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}