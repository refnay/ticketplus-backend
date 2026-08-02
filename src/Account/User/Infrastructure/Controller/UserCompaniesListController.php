<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\ListCompanies\ListUserCompaniesQuery;
use App\Account\User\Application\ListCompanies\UserCompaniesResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserCompaniesListController extends AbstractController
{
    public function list(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->typeAllowed();
        $session->statusAllowed();

        $query = ListUserCompaniesQuery::fromQuery($request->query->all());
        $query->setSession($session);
        
        /** @var UserCompaniesResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}