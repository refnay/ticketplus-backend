<?php

namespace App\Identity\User\Infrastructure\Controller;

use App\Identity\User\Application\Create\CreateUserCommand;
use App\Identity\User\Application\Create\CreateUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterController extends AbstractController
{
    public function create(Request $request, CreateUserCommandHandler $handler): JsonResponse
    {
        $command = CreateUserCommand::fromRequest($request->toArray());
        
        /** @var string $response */
        $response = $handler->__invoke($command);

        return new JsonResponse(['token' => $response]);
    }
}