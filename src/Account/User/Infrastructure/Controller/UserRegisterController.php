<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Create\CreateUserCommand;
use App\Shared\Application\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterController extends AbstractController
{
    public function create(Request $request, MessageBus $messageBus): JsonResponse
    {
        $command = CreateUserCommand::create($request->toArray());
        
        /** @var string $response */
        $response = $messageBus->dispatch($command);

        return new JsonResponse(['token' => $response]);
    }
}