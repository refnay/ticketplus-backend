<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Company\Find\FindUserCompanyQuery;
use App\Account\User\Application\Company\Find\UserCompanyResponse;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserCompanyFindController extends AbstractController
{
    public function find(Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindUserCompanyQuery::create();
        $query->setSession($session);
        
        /** @var UserCompanyResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}