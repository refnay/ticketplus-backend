<?php

namespace App\Account\CompanyMember\Infrastructure\Controller;

use App\Account\CompanyMember\Application\Company\SearchCompany\CompanyMemberCompanyResponse;
use App\Account\CompanyMember\Application\Company\SearchCompany\SearchCompanyMemberCompanyQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyMemberCompanySearchController extends AbstractController
{
    public function search(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->userTypeAllowed();
        $session->userStatusAllowed();

        $query = SearchCompanyMemberCompanyQuery::fromQuery($request->query->all());
        $query->setSession($session);
        
        /** @var CompanyMemberCompanyResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}