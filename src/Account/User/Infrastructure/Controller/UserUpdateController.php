<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Update\UpdateUserCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserUpdateController extends AbstractController
{
    public function update(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = UpdateUserCommand::create($request->toArray());
        $command->setSession($session);
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}