<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Update\UpdateUserCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserUpdateController extends AbstractController
{
    public function update(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = UpdateUserCommand::create($request->toArray());
        
        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
