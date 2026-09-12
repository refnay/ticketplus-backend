<?php

namespace App\Account\Member\Infrastructure\Controller;

use App\Account\Member\Application\Find\MemberResponse;
use App\Account\Member\Application\Find\FindMemberQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemberFindController extends AbstractController
{
    public function find(QueryBus $queryBus): JsonResponse
    {
        $query = FindMemberQuery::create();

        /** @var MemberResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
