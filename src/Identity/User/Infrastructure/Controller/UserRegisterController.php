<?php

namespace App\Identity\User\Infrastructure\Controller;

use App\Identity\User\Application\Create\CreateUserCommand;
use App\Identity\User\Application\Create\CreateUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserRegisterController extends AbstractController
{
    #[Route('/register', name: 'user-register', methods: ['POST'])]
    public function create(Request $request, CreateUserCommandHandler $handler): JsonResponse
    {
        $command = CreateUserCommand::fromRequest($request->toArray());
        
        /** @var string $response */
        $response = $handler->__invoke($command);

        return new JsonResponse(['token' => $response]);
    }
}