<?php

namespace App\Identity\User\Infrastructure\Controller;

use App\Identity\User\Application\Find\FindUserQuery;
use App\Identity\User\Application\Find\UserResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserFindController extends AbstractController
{
    public function find(Session $session, MessageBus $messageBus): JsonResponse
    {
        $query = FindUserQuery::create();
        $query->setSession($session);
        
        /** @var UserResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->toArray());
    }
}