<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Company\Search\UserCompaniesResponse;
use App\Account\User\Application\Company\Search\SearchUserCompanyQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserCompanySearchController extends AbstractController
{
    public function search(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->userTypeAllowed();
        $session->userStatusAllowed();

        $query = SearchUserCompanyQuery::fromQuery($request->query->all());
        $query->setSession($session);
        
        /** @var UserCompaniesResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}