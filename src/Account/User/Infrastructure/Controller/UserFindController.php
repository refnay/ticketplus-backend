<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Find\FindUserQuery;
use App\Account\User\Application\Find\UserResponse;
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