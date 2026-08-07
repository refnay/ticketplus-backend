<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\Company\Switch\SwitchUserCompanyCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserCompanySwitchController extends AbstractController
{
    public function switch(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->userTypeAllowed();
        $session->userStatusAllowed();
        
        $command = SwitchUserCompanyCommand::create($request->toArray());
        $command->setSession($session);
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}