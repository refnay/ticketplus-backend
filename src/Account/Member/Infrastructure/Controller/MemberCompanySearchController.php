<?php

namespace App\Account\Member\Infrastructure\Controller;

use App\Account\Member\Application\SearchCompany\MemberCompanyResponse;
use App\Account\Member\Application\SearchCompany\SearchMemberCompanyQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MemberCompanySearchController extends AbstractController
{
    public function search(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchMemberCompanyQuery::fromQuery($request->query->all());

        /** @var MemberCompanyResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
