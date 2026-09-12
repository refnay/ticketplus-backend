<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Register\CreateUserCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterController extends AbstractController
{
    public function create(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = CreateUserCommand::create($request->toArray());

        /** @var string $response */
        $response = $commandBus->dispatch($command);

        return new JsonResponse(['token' => $response]);
    }
}