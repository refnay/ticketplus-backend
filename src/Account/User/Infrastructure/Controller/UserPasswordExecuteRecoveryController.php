<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\ExecutePasswordRecovery\ExecuteUserPasswordRecoveryCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordExecuteRecoveryController extends AbstractController
{
    public function execute(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = ExecuteUserPasswordRecoveryCommand::create($request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}