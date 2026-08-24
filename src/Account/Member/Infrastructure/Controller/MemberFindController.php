<?php

namespace App\Account\Member\Infrastructure\Controller;

use App\Account\Member\Application\Find\MemberResponse;
use App\Account\Member\Application\Find\FindMemberQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemberFindController extends AbstractController
{
    public function find(Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindMemberQuery::create();
        $query->setSession($session);
        
        /** @var MemberResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
