<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\UpdatePassword\UpdateUserPasswordCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserPasswordUpdateController extends AbstractController
{
    public function update(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = UpdateUserPasswordCommand::create($request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
