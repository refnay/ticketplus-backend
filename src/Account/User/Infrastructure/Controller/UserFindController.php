<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Find\FindUserQuery;
use App\Account\User\Application\Find\UserResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserFindController extends AbstractController
{
    public function find(QueryBus $queryBus): JsonResponse
    {
        $query = FindUserQuery::create();

        /** @var UserResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
