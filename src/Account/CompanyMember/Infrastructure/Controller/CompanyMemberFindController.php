<?php

namespace App\Account\CompanyMember\Infrastructure\Controller;

use App\Account\CompanyMember\Application\Company\Find\CompanyMemberResponse;
use App\Account\CompanyMember\Application\Company\Find\FindCompanyMemberQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyMemberFindController extends AbstractController
{
    public function find(Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindCompanyMemberQuery::create();
        $query->setSession($session);
        
        /** @var CompanyMemberResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}