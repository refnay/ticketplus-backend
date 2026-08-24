<?php

namespace App\Account\Member\Infrastructure\Controller;

use App\Account\Member\Application\SearchCompany\MemberCompanyResponse;
use App\Account\Member\Application\SearchCompany\SearchMemberCompanyQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MemberCompanySearchController extends AbstractController
{
    public function search(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->userTypeAllowed();
        $session->userStatusAllowed();

        $query = SearchMemberCompanyQuery::fromQuery($request->query->all());
        $query->setSession($session);
        
        /** @var MemberCompanyResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
