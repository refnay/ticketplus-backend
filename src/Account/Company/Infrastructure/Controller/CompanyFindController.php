<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\Find\CompanyResponse;
use App\Account\Company\Application\Find\FindCompanyQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyFindController extends AbstractController
{
    public function find(QueryBus $queryBus): JsonResponse
    {
        /** @var CompanyResponse $response */
        $response = $queryBus->ask(FindCompanyQuery::create());

        return new JsonResponse($response->jsonSerialize());
    }
}
