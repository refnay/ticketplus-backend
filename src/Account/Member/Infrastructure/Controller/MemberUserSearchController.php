<?php

namespace App\Account\Member\Infrastructure\Controller;

use App\Account\Member\Application\SearchUser\MemberUsersResponse;
use App\Account\Member\Application\SearchUser\SearchMemberUserQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MemberUserSearchController extends AbstractController
{
    public function search(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchMemberUserQuery::fromQuery($request->query->all());

        /** @var MemberUsersResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
